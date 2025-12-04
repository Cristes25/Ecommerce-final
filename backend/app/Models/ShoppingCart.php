<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = "shopping_carts";
    protected $primaryKey = "cart_id";

    protected $fillable = [
        "user_id"
    ];
    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];
    //Eloquent Relationships
    //ShoppingCart belongs to User N:1
    public function user(){
        return $this->belongsTo(User::class, "user_id", "user_id");
    }
    //ShoppingCart has many CartItems 1:N
    public function cartItems(){
        return $this->hasMany(CartItems::class, "cart_id", "cart_item_id");
    }
}
