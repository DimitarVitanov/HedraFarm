<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\CompanyInfo;
use Illuminate\Support\Carbon;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function getOrders(){
        $orders = Order::with('items')->latest()->get()->map(function($order){
            return[
                'id' => $order->id,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
                'city' => $order->city,
                'municipality' => $order->municipality,
                'postal_code' => $order->postal_code,
                'country' => $order->country,
                'additional_description' => $order->additional_description,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'subtotal' => $order->subtotal,
                'discount' => $order->discount,
                'delivery_price' => $order->delivery_price,
                'total_price' => $order->total_price,
                'items' => $order->items,
                'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:i'),
            ];
        });
        return response()->json(['status' => 'success', 'data' => $orders]);
    }
    public function store(Request $request)
    {
        $cart = json_decode($request->cart, true);

        $subtotal = collect($cart['items'])->sum(fn($item) => $item['price'] * $item['quantity']);

        $order = Order::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'municipality' => $request->municipality,
            'postal_code' => $request->postalCode,
            'country' => $request->country,
            'additional_description' => $request->additionalDescription,
            'payment_method' => $request->paymentMethod,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount' => $request->discount,
            'delivery_price' => $request->deliveryPrice,
            'total_price' => $request->totalPrice,
        ]);

        foreach ($cart['items'] as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'title' => $item['title'] ?? $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json(['status' => 'success', 'data' => $order, 'message' => 'Order created successfully.']);
    }

    public function approveOrder(Request $request)
    {
        $order = Order::with('items.product')->whereId($request->order_id)->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found.']);
        }

        // Check if order is already approved
        if ($order->status === 'completed') {
            return response()->json(['status' => 'error', 'message' => 'Order is already approved.']);
        }

        try {
            DB::transaction(function () use ($order) {
                // Validate stock availability for all items before proceeding
                foreach ($order->items as $item) {
                    if (!$item->product) {
                        throw new \Exception("Product not found for order item: {$item->title}");
                    }

                    // Refresh product data to get latest quantity
                    $item->product->refresh();

                    if ($item->product->quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for product: {$item->title}. Available: {$item->product->quantity}, Required: {$item->quantity}");
                    }
                }

                // Deduct quantities from products
                foreach ($order->items as $item) {
                    $item->product->decrement('quantity', $item->quantity);
                }

                // Update order status to completed
                $order->update(['status' => 'completed']);
            });

            // Send approval email (outside transaction to avoid rollback on email failures)
            Mail::to($order->email)->send(new OrderMail([
                'subject' => 'Ваша нарачка е одобрена!',
                'title' => 'Вашата нарачка е одобрена!',
                'message' => 'Ваша нарачка е одобрена и ќе биде испратена во најскоро време. <br> Ви благодариме!',
                'button_url' => 'https://hederafarmplus.mk',
                'button_text' => 'Посетете ја нашата веб страна',
                'order' => $order
            ]));

            return response()->json(['status' => 'success', 'message' => 'Order approved successfully and product quantities updated.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function cancelOrder(Request $request)
    {
        $order = Order::with('items.product')->whereId($request->order_id)->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found.']);
        }

        // Check if order is already cancelled
        if ($order->status === 'cancelled') {
            return response()->json(['status' => 'error', 'message' => 'Order is already cancelled.']);
        }

        try {
            DB::transaction(function () use ($order) {
                // If the order was completed, restore the product quantities
                if ($order->status === 'completed') {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increment('quantity', $item->quantity);
                        }
                    }
                }

                // Update order status to cancelled
                $order->update(['status' => 'cancelled']);
            });

            return response()->json(['status' => 'success', 'message' => 'Order cancelled successfully. Product quantities have been restored if applicable.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function downloadInvoice($id)
    {
        $order = Order::with('items')->findOrFail($id);

        $logoPath = public_path('assets/img/logo/logo.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $company = CompanyInfo::first();

        $pdf = Pdf::loadView('invoices.order', [
            'order' => $order,
            'logoBase64' => $logoBase64,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
