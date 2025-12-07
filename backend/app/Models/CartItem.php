<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = "cart_items";
    protected $primaryKey = "cart_item_id";
    protected $fillable = [
        "cart_id",
        "product_id",
        "quantity"
    ];
    //Eloquent Relationships
    //CartItem belongs to ShoppingCart N:1
    public function shoppingCart(){
        return $this->belongsTo(ShoppingCart::class, "cart_id", "cart_id");
    }
    //CartItem belongs to Product N:1
    public function product(){
        return $this->belongsTo(Product::class, "product_id", "product_id");
    }
}
