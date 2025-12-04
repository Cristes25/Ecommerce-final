<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "order_items";
    protected $primaryKey = "item_id";

    protected $fillable =[
        "order_id",
        "product_id",
        "quantity",
        "unit_price",
    ];

    protected $casts = [
        "unit_price" => "decimal:2"
    ];

    //Eloquent Relationships
    //OrderItems belongs to one Order N:1
    public function order(){
        return $this->belongsTo(Orders::class, "order_id", "order_id");
    }
    //OrderItems belongs to one Product N:1
    public function product(){
        return $this->belongsTo(Products::class, "product_id", "product_id");
    }

}
