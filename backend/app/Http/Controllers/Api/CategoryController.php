<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories], 200);
    }

    // Show a single category with products
    public function show($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['category' => $category], 200);
    }

    // Create a new category (ADMIN ONLY)
    public function store(Request $request)
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized. Administrator access required'], 403);
        }

        $validated = $request->validate([
            'cat_name' => 'required|string|max:25|unique:categories,cat_name',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    // Update an existing category (ADMIN ONLY)
    public function update(Request $request, $id)
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized. Administrator access required'], 403);
        }

        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validated = $request->validate([
            'cat_name' => 'required|string|max:25|unique:categories,cat_name,' . $id . ',category_id',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ], 200);
    }

    // Delete a category (ADMIN ONLY)
    public function destroy($id)
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized. Administrator access required'], 403);
        }

        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
