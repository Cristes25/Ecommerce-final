<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return response()->json([
            'products' => $products
        ], 200);
    }

    // Show a single product
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product
        ], 200);
    }

    // Store a new product (ADMIN ONLY)
    public function store(Request $request)
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized. Administrator access required'], 403);
        }

        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,category_id',
            'prod_name'      => 'required|string|max:50',
            'prod_description'=> 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'image_path'     => 'nullable|string|max:255',
            'image_url'      => 'nullable|url|max:255',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    // Update a product (ADMIN ONLY)
    public function update(Request $request, $id)
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized. Administrator access required'], 403);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'category_id'    => 'sometimes|exists:categories,category_id',
            'prod_name'      => 'sometimes|string|max:50',
            'prod_description'=> 'nullable|string',
            'price'          => 'sometimes|numeric|min:0',
            'image_path'     => 'nullable|string|max:255',
            'image_url'      => 'nullable|url|max:255',
            'stock_quantity' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    // Delete a product (ADMIN ONLY)
    public function destroy($id)
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized. Administrator access required'], 403);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}

