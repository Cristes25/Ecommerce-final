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
        Schema::create('users', function (Blueprint $table) {
            // Primary Key
            $table->id('user_id');
            //name and email colinms 
            $table->string('firstName', 25);
            $table->string('lastName', 25);
            $table->string('email', 50)->unique();
            //password hash column
            $table->string('password_hash', 255);
            //user role column with default 'customer'
            $table->enum('user_role',['customer', 'administrator'])->default('customer');
            //laravel fields 
            $table ->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("users");
     
    }
};
