<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Mail;

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
        $order = Order::with('items')->whereId($request->order_id)->first();
        if ($order) {
            //$order->update(['status' => 'completed']);
            Mail::to($order->email)->send(new OrderMail([
                'subject' => 'Ваша нарачка е одобрена!',
                'title' => 'Нарачка одобрена!',
                'message' => 'Ваша нарачка е одобрена и ќе биде испратена во најскоро време. <br> Ви благодариме!',
                'button_url' => 'https://hederafarmplus.mk',
                'button_text' => 'Посетете ја нашата веб страна',
                'order' => $order
            ]));

            return response()->json(['status' => 'success', 'message' => 'Order approved successfully.']);
        }
        return response()->json(['status' => 'error', 'message' => 'Order not found.']);
    }
}
