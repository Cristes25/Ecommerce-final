<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $primary_key = "product_id";

    protected $fillable= [
        "prod_name",
        "prod_description",
        "price",
        "stock_quantity",
        "price",
        "image_path",
        "image_url"

    ];
    protected $casts = [
        "price"=> "decimal:2",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];
    // Accessor to dynamically generate the full image URL
    protected function imageUrl(): Attribute
    {
        // The url() helper builds the full domain + path to the /public/storage link
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => url("storage/" . $attributes["image_path"]),
        );
    }

    //Eloquent Relationships
    //Product belongs to Category N:1
    public function category(){
        //fk category_id in products table
        return $this->belongsTo(Category::class, "category_id", "category_id");
    }
}
