<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("cart_items", function (Blueprint $table) {
            //pk
            $table->id("cart_item_id");
            //fk 4 shopping cart
            $table ->foreignId("cart_id")->constrained("shopping_cart","cart_id")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            //fk 4 products
            $table ->foreignId("product_id")->constrained("products","product_id")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            //quantity
            $table->unsignedInteger("quantity");            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("cart_items");
    }
};
