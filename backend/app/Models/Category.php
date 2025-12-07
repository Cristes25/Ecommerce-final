<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $primaryKey = "category_id";
    protected $fillable = [
        "cat_name",
    ];
    protected $casts = [
        "created_at" => "datetime",
        "updated_at"=> "datetime",
    ];
    //eloquent relations 
    //product belongs to Category N:1
    public function category(){
        return $this->belongsTo(Category::class, "category_id", "category_id");
    }
    //product has many orderItems 1:N
    public function orderItems(){
        return $this->hasMany(OrderItem::class, "product_id", "product_id");
    }
    public function cartItems(){
        return $this->hasMany(CartItem::class, "product_id", "product_id");
    }

}
