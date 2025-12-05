<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
      // List orders
    public function index()
    {
        $orders = Gate::allows('is-admin')
            ? Order::with('orderItems.product', 'user')->latest()->get()
            : Order::with('orderItems.product')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

        return response()->json(['orders' => $orders], 200);
    }

    // Show a single order
    public function show($id)
    {
        $order = Order::with('orderItems.product', 'user')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (Gate::denies('is-admin') && $order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['order' => $order], 200);
    }

    // Checkout: place order from cart
    public function checkout(Request $request)
    {
        $cart = ShoppingCart::with('cartItems.product')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $request->validate([
            'shipping_address' => 'required|string|max:255',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'shipping_address' => $request->shipping_address,
            'status' => 'pending',
            'total_price' => 0,
        ]);

        $total = 0;

        foreach ($cart->cartItems as $item) {
            $product = $item->product;

            if ($product->stock_quantity < $item->quantity) {
                return response()->json([
                    'message' => "Insufficient stock for product {$product->prod_name}"
                ], 400);
            }

            $price = $product->price * $item->quantity;
            $total += $price;

            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $product->product_id,
                'quantity' => $item->quantity,
                'price' => $price,
            ]);

            $product->decrement('stock_quantity', $item->quantity);
        }

        // Update order total
        $order->update(['total_price' => $total]);

        // Clear cart
        $cart->cartItems()->delete();

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order->load('orderItems.product')
        ], 201);
    }

    // Update order status (admin only)
    public function updateStatus(Request $request, $id)
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ], 200);
    }
}
