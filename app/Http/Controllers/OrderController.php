<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'email' => 'required|email',
            'guest_name' => 'nullable|string',
            'guest_phone' => 'nullable|string',
        ]);
        $order = Order::create([
            // 'user_id' => auth()->check() ? auth()->id() : null,
            'user_id' => Auth::check() ? Auth::id() : null,
            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'email' => $request->email,
            'shipping_address' => $request->shipping_address,
            'status' => 'pending',
            'total_amount' => 0,
        ]);
        $totalAmount = 0;
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $totalAmount += $product->price * $productData['quantity'];

            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'price' => $product->price,
            ]);
        }
        $order->update(['total_amount' => $totalAmount]);
        return Response::json(["message" => "Order placed successfully", "order" => $order], 201);
    }
}
