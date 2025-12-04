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
        Schema::create("products", function (Blueprint $table) {
            //pk
            $table->id("product_id");
            //fk to categories
            $table->foreignId("category_id")->nullable()->constrained("categories","category_id")
                ->onDelete("set null")
                ->onUpdate("cascade");
            //product details 
            $table->string("prod_name", 50);
            $table->text("prod_description")->nullable();
            //finance
            $table->decimal("price", 6, 2);
            $table->unsignedInteger("stock_quantity")->default(0);
            //image
            $table->string("image_url")->nullable();
            $table->string("image_path")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
