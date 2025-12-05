<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class ShoppingCartController extends Controller
{
     // Get the current user's cart
    public function index()
    {
        $cart = ShoppingCart::with('cartItems.product')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json(['cart' => $cart], 200);
    }

    // Add a product to the cart
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = ShoppingCart::firstOrCreate(
            ['user_id' => Auth::id()]
        );

        $product = Product::find($validated['product_id']);

        // Check if product already in cart
        $cartItem = CartItem::where('shopping_cart_id', $cart->shopping_cart_id)
            ->where('product_id', $product->product_id)
            ->first();

        if ($cartItem) {
            // Increment quantity
            $cartItem->increment('quantity', $validated['quantity']);
        } else {
            CartItem::create([
                'shopping_cart_id' => $cart->shopping_cart_id,
                'product_id' => $product->product_id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json(['message' => 'Product added to cart', 'cart' => $cart->load('cartItems.product')], 201);
    }

    // Update quantity of a cart item
    public function updateItem(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::find($itemId);

        if (!$cartItem || $cartItem->shoppingCart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Cart item not found or unauthorized'], 404);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json(['message' => 'Cart item updated', 'cart' => $cartItem->shoppingCart->load('cartItems.product')], 200);
    }

    // Remove a product from the cart
    public function removeItem($itemId)
    {
        $cartItem = CartItem::find($itemId);

        if (!$cartItem || $cartItem->shoppingCart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Cart item not found or unauthorized'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed', 'cart' => $cartItem->shoppingCart->load('cartItems.product')], 200);
    }

    // Clear the cart
    public function clear()
    {
        $cart = ShoppingCart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->cartItems()->delete();

        return response()->json(['message' => 'Cart cleared', 'cart' => $cart->load('cartItems.product')], 200);
    }
}
