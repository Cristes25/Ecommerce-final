<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    protected $fillable=[
        "user_id",
        "total_price",
        "shipping_address",
        "order_status",
    ];
    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];
    //Eloquent Relationships
    //Order belongs to User N:1
    public function user(){
        return $this->belongsTo(User::class, "user_id", "user_id");
    }

    //Order has many OrderItems 1:N
    public function OrderItems(){
        return $this->hasMany(OrderItems::class, "order_id", "order_id");
    }
}
