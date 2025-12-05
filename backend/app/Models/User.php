<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // override default primary key
    protected $primaryKey = 'user_id';

    //fillabel fields 
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password_hash',  // map to 'password' laracevel default
        'user_role'  // e.g., 'admin', 'customer' default customer 
    ];
    //sensitive fields
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [

        'user_role' => 'string',
    ];

    public function getAuthPassword(): string{
        return $this->password_hash;
    }
    //Eloquent Relationships
    // A user can have many orders 1:N
    public function orders(){
        return $this->hasMany(Order::class);

    }
    //A user has one cart 1:1
    public function shoppingcart(){
        return $this->hasOne(ShoppingCart::class);
    }
    //boolean isAdmin
    public function isAdmin(){
        return $this-> user_role === 'administrator';
    }  
}
