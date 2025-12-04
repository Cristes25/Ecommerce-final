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
        Schema::create("order_items", function (Blueprint $table) {
            $table->id("item_id");
            //fk to orders table
            $table->foreignId("order_id")->constrained("orders", "order_id")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            //fk to products table
            $table->foreignId("product_id")->constrained("products", "product_id")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            //quantity
            $table->unsignedInteger("quantity");
            //price
            $table->decimal("price", 6, 2);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("order_items");
    }
};
