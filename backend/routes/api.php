<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

use App\Models\Category;
use App\Models\ShoppingCart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// --------------------
// Public Routes
// --------------------

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);

});
Route::get('/test-product', function() {
    return Product::all();
});

Route::get('/categories', function() {
    return Category::all();
});


// Auth: Register
Route::post('/register', function(Request $request) {
    $request->validate([
        'firstName' => 'required|string|max:25',
        'lastName' => 'required|string|max:25',
        'email'=> 'required|string|email|unique:users,email|max:50',
        'password'=> 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'firstName'=>$request->firstName,
        'lastName'=>$request->lastName,
        'email'=>$request->email,
        'password_hash'=> Hash::make($request->password),
    ]);

    ShoppingCart::create([
        'user_id'=>$user->user_id,
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message'=> 'User registered successfully',
        'access_token'=> $token,
        'token_type'=> 'Bearer',
        'user'=> $user->only(['user_id', 'firstName', 'lastName', 'email', 'user_role']),
    ], 201);
});

// Auth: Login
Route::post('/login', function(Request $request){
    $request->validate([
        'email'=> 'required|email',
        'password'=> 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if(!$user || !Hash::check($request->password, $user->password_hash)){
        throw ValidationException::withMessages([
            'email'=> ['Provided credentials are incorrect']
        ]);
    }

    $user->tokens()->delete();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message'=> 'Login successful',
        'access_token'=> $token,
        'token_type'=> 'Bearer',
        'user'=> $user->only(['user_id', 'firstName','lastName', 'email', 'user_role']),
    ]);
});

// Products
Route::get('/products', function() {
    return Product::all();
});

Route::get('/products/{id}', function($id) {
    $product = Product::find($id);
    if(!$product) return response()->json(['message'=>'Product not found'],404);
    return $product;
});

// Categories
Route::get('/categories', function() {
    return Category::all();
});

Route::get('/categories/{id}', function($id) {
    $category = Category::with('products')->find($id);
    if(!$category) return response()->json(['message'=>'Category not found'],404);
    return $category;
});

// --------------------
// Protected Routes
// --------------------
Route::middleware('auth:sanctum')->group(function() {

    // Logout
    Route::post('/logout', function(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out successfully']);
    });

    // Admin Product Management
    Route::post('/products', function(Request $request){
        if($request->user()->user_role !== 'administrator'){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        $request->validate([
            'name'=>'required|string|max:50',
            'price'=>'required|numeric'
        ]);
        $product = Product::create($request->all());
        return response()->json(['message'=>'Product created','product'=>$product],201);
    });

    Route::put('/products/{id}', function(Request $request, $id){
        if($request->user()->user_role !== 'administrator'){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        $product = Product::find($id);
        if(!$product) return response()->json(['message'=>'Product not found'],404);
        $product->update($request->all());
        return response()->json(['message'=>'Product updated','product'=>$product]);
    });

    Route::delete('/products/{id}', function(Request $request, $id){
        if($request->user()->user_role !== 'administrator'){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        $product = Product::find($id);
        if(!$product) return response()->json(['message'=>'Product not found'],404);
        $product->delete();
        return response()->json(['message'=>'Product deleted']);
    });

    // Admin Category Management
    Route::post('/categories', function(Request $request){
        if($request->user()->user_role !== 'administrator'){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        $request->validate(['cat_name'=>'required|string|max:25|unique:categories']);
        $category = Category::create($request->all());
        return response()->json(['message'=>'Category created','category'=>$category],201);
    });

    Route::put('/categories/{id}', function(Request $request,$id){
        if($request->user()->user_role !== 'administrator'){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        $category = Category::find($id);
        if(!$category) return response()->json(['message'=>'Category not found'],404);
        $category->update($request->all());
        return response()->json(['message'=>'Category updated','category'=>$category]);
    });

    Route::delete('/categories/{id}', function(Request $request,$id){
        if($request->user()->user_role !== 'administrator'){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        $category = Category::find($id);
        if(!$category) return response()->json(['message'=>'Category not found'],404);
        $category->delete();
        return response()->json(['message'=>'Category deleted']);
    });

    // Shopping Cart
    Route::get('/cart', function(Request $request){
        $cart = ShoppingCart::where('user_id',$request->user()->user_id)->with('items')->first();
        return $cart;
    });

    Route::post('/cart', function(Request $request){
        // add item logic
    });

    Route::put('/cart/{itemId}', function(Request $request,$itemId){
        // update item logic
    });

    Route::delete('/cart/{itemId}', function(Request $request,$itemId){
        // remove item logic
    });

    Route::delete('/cart', function(Request $request){
        // clear cart logic
    });

    // Orders
    Route::get('/orders', function(Request $request){
        return Order::where('user_id',$request->user()->user_id)->get();
    });

    Route::get('/orders/{id}', function(Request $request, $id){
        $order = Order::find($id);
        if(!$order) return response()->json(['message'=>'Order not found'],404);
        return $order;
    });

    Route::post('/checkout', function(Request $request){
        // checkout logic
    });

    Route::patch('/orders/{id}/status', function(Request $request, $id){
        if($request->user()->user_role !== 'administrator'){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        // update order status logic
    });
});
