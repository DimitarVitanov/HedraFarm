<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function getOrders(){
        $orders = Order::with('items')->latest()->get();
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
}
