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
        Schema::create("orders", function (Blueprint $table) {
            $table->id("order_id");
            //fk to users table
            $table->foreignId("user_id")->constrained("users", "user_id")
                ->onDelete("restrict") //user can not be deleted if they have orders
                ->onUpdate("cascade");
            //Finance things 
            $table->decimal("total_price", 6, 2);
            $table->string("shipping_address", 50);
            //status 
            $table->enum("order_status", ["pending", "shipped", "processed", "cancelled"])->default("pending");
            //laravel fields
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("orders");
    }
};
