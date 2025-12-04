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
        Schema::create("shopping_cart", function (Blueprint $table) {
            //primary key
            $table->id("cart_id");
            //foreign key to users 1:1
            //Linsk back user_id in users table
            //unique ro ensure 1:1 relationship
            $table->foreignId("user_id")->constrained("users", "user_id")
                ->onDelete("cascade")
                ->onUpdate("cascade");
            //laravel fields
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("shopping_cart");
    }
};
