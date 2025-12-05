<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:25',
            'lastName' => 'required|string|max:25',
            'email'=> 'required|string|email|unique:users,email|max:50',
            'password'=> 'required|string|min:8|confirmed',
        ]);

    //create user record in the db 
    $user = User::create([
        'firstName'=>$request->firstName,
        'lastName'=>$request->lastName,
        'email'=>$request->email,
        'password_hash'=> Hash::make($request->password), //use Hash::make() on the custom 'password_hash' column
        
    ]);
    //create an empty shopping cart for the new user
    ShoppingCart::create([
        'user_id'=>$user->user_id,
    ]);
    //sanctum token generation
    $token = $user->createToken('auth_token')->plainTextToken;

    //response 
    return response()->json([
        'message'=> 'User registered successfully',
        'access_token'=> $token,
        'token_type'=> 'Bearer',
        'user'=> $user->only(['user_id', 'firstName', 'lastName', 'email', 'user_role']),
    ], 201);
    }
    /*
    Handle user login
    Validates user credentials and returns an auth token
    */ 
    public function login(Request $request)
    {
       //validarion 
       $request->validate([
        'email'=> 'required|email',
        'password'=> 'required',
       ]);
       //find user by email
       $user = User::where('email',$request->email)->first();
       //check if user exist and if provided password matches the stored hash
       if(!$user || !Hash::check($request->password, $user->password_hash)){
        //validation exception
        throw ValidationException::withMessages([
            'email'=> ['Provided credentials are incorrect']
        ]);
       }
       //revoke existing tokens
         $user->tokens()->delete();
       //generate new token
         $token = $user->createToken('auth_token')->plainTextToken;

       //success response 
       return response()->json([
        'message'=> 'Login successful',
        'access_token'=> $token,
        'token_type'=> 'Bearer',
        'user'=> $user->only(['user_id', 'firstName','lastName', 'email', 'user_role']),
       ], 200);
    }
    /**
     * Handle logout 
     * Revokes the current user's auth token
     * require ''auth:sanctum' middleware on the route 
     */
    public function logout(request $request){
        //revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message'=> 'Logged out successfully'
        ], 200); //should implement redirect on frontend
    }

}
