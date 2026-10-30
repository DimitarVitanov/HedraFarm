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

        // Send "order received" email after the response so a slow/failing mail server never blocks checkout
        dispatch(function () use ($order) {
            try {
                $order->load('items');
                Mail::to($order->email)->send(new OrderMail([
                    'subject' => 'Ја примивме вашата нарачка #' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'title' => 'Ви благодариме за вашата нарачка!',
                    'message' => "Почитувани {$order->first_name},\nВашата нарачка е успешно примена. Наскоро ќе стапиме во контакт со вас за да ја потврдиме нарачката.\nПлаќањето се врши при достава.",
                    'button_url' => 'https://hederafarmplus.mk',
                    'button_text' => 'Посетете ја нашата веб страна',
                    'order' => $order
                ]));
            } catch (\Throwable $e) {
                report($e);
            }
        })->afterResponse();

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
                'message' => "Вашата нарачка е одобрена и ќе биде испратена во најскоро време.\nВи благодариме!",
                'button_url' => 'https://hederafarmplus.mk',
                'button_text' => 'Посетете ја нашата веб страна',
                'order' => $order
            ]));

            return response()->json(['status' => 'success', 'message' => 'Order approved successfully and product quantities updated.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Adjust an order before/after fulfilment (item out of stock, smaller quantity...).
    // items: JSON [{id, quantity}] for every order item; quantity 0 removes the item.
    public function update(Request $request)
    {
        $order = Order::with('items.product')->whereId($request->order_id)->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found.']);
        }

        if ($order->status === 'cancelled') {
            return response()->json(['status' => 'error', 'message' => 'Cancelled orders cannot be edited.']);
        }

        $quantities = collect(json_decode($request->items, true) ?? [])
            ->mapWithKeys(fn($item) => [(int) ($item['id'] ?? 0) => (int) ($item['quantity'] ?? 0)]);

        if ($quantities->contains(fn($quantity) => $quantity < 0)) {
            return response()->json(['status' => 'error', 'message' => 'Quantities cannot be negative.']);
        }

        $remaining = $order->items->filter(fn($item) => $quantities->get($item->id, (int) $item->quantity) > 0);
        if ($remaining->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'An order needs at least one item. Cancel the order instead.']);
        }

        try {
            DB::transaction(function () use ($order, $quantities, $request) {
                foreach ($order->items as $item) {
                    $newQuantity = $quantities->get($item->id, (int) $item->quantity);
                    $difference = $newQuantity - (int) $item->quantity;

                    // Completed orders already took their stock - give back / take the difference
                    if ($order->status === 'completed' && $difference !== 0 && $item->product) {
                        $item->product->refresh();
                        if ($difference > 0 && (int) $item->product->quantity < $difference) {
                            throw new \Exception("Insufficient stock for product: {$item->title}. Available: {$item->product->quantity}, Required: {$difference}");
                        }
                        $difference > 0
                            ? $item->product->decrement('quantity', $difference)
                            : $item->product->increment('quantity', -$difference);
                    }

                    if ($newQuantity === 0) {
                        $item->delete();
                    } elseif ($difference !== 0) {
                        $item->update(['quantity' => $newQuantity]);
                    }
                }

                $subtotal = $order->items()->get()->sum(fn($item) => $item->price * $item->quantity);
                $discount = min(max(0, (float) $request->discount), $subtotal);
                $deliveryPrice = max(0, (float) $request->delivery_price);

                $order->update([
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'delivery_price' => $deliveryPrice,
                    'total_price' => $subtotal - $discount + $deliveryPrice,
                ]);
            });

            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
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

            // Send cancellation email after the response so a mail failure doesn't report the cancel as failed
            dispatch(function () use ($order) {
                try {
                    $phone = CompanyInfo::first()?->phone;
                    Mail::to($order->email)->send(new OrderMail([
                        'subject' => 'Вашата нарачка #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' е откажана',
                        'title' => 'Вашата нарачка е откажана',
                        'message' => "Почитувани {$order->first_name},\nВе известуваме дека вашата нарачка е откажана.\n"
                            . ($phone ? "Доколку имате прашања, контактирајте нè на {$phone}." : 'Доколку имате прашања, слободно контактирајте нè.'),
                        'button_url' => 'https://hederafarmplus.mk',
                        'button_text' => 'Посетете ја нашата веб страна',
                        'total_label' => 'Вкупно',
                        'order' => $order
                    ]));
                } catch (\Throwable $e) {
                    report($e);
                }
            })->afterResponse();

            return response()->json(['status' => 'success', 'message' => 'Order cancelled successfully. Product quantities have been restored if applicable.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function downloadInvoice(Request $request, $id)
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

        $fileName = 'invoice-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        // ?preview=1 opens the PDF in the browser instead of downloading it
        return $request->boolean('preview') ? $pdf->stream($fileName) : $pdf->download($fileName);
    }
}
