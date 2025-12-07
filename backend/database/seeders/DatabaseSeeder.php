<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ShoppingCart;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // Create users with different roles
        $admin= User::create([
            'firstName'=> 'Cristiana',
            'lastName'=> 'Espinoza',
            'email'=> 'admin@goodandgraces.com',
            'password_hash'=> Hash::make('password'),
            'user_role'=> 'administrator',
        ]);
        ShoppingCart::create([
            'user_id'=>$admin->user_id,
        ]);
        //customer user
        $customer= User::create([
            'firstName'=> 'John',
            'lastName'=> 'Customer',
            'email'=> 'johncustomer@goodandgraces.com',
            'password_hash'=> Hash::make('password'),
            'user_role'=> 'customer',
        ]);
        ShoppingCart::create([
            'user_id'=>$customer->user_id,
        ]);
        // Themed Categories
        DB::table('categories')->insert([
            ['cat_name'=> 'Books', 'created_at'=> now(), 'updated_at'=> now()],
            ['cat_name'=> 'Bibles', 'created_at'=> now(), 'updated_at'=> now()],
            ['cat_name'=> 'Devotionals', 'created_at'=> now(), 'updated_at'=> now()],
            ['cat_name'=> 'Journal & Supplies', 'created_at'=> now(), 'updated_at'=> now()],
            ['cat_name'=> 'Decor', 'created_at'=> now(), 'updated_at'=> now()],
        ]);
        $booksId = DB::table('categories')->where('cat_name', 'Books')->value('category_id');
        $biblesId = DB::table('categories')->where('cat_name', 'Bibles')->value('category_id');
        $devotionalsId = DB::table('categories')->where('cat_name', 'Devotionals')->value('category_id');
        $journalsSuppliesId = DB::table('categories')->where('cat_name', 'Journal & Supplies')->value('category_id');
        $decorId = DB::table('categories')->where('cat_name', 'Decor')->value('category_id');
        // Products
        DB::table('products')->insert([
            //books product
            //For real books product names/logos belong to their rightful owners
            'category_id'=> $booksId,
            'prod_name'=> 'To Be a Woman',
            'prod_description'=> 'Christian perspective on modern gender identity debates, offering biblical insight and practical guidance to help readers understand and respond to cultural confusion about what it means to be a woman',
            'price'=> 14.99,
            'stock_quantity'=> 7,
            'image_path'=>'tobeawoman.jpg',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('products')->insert([
            //Devotionals
            'category_id'=> $devotionalsId,
            'prod_name'=> 'He Whispers Yours Name',
            'prod_description'=> 'He Whispers Your Name is 365 days devotional offering inspiration for your daily walk of faith.',
            'price'=> 12.99,
            'stock_quantity'=> 80, //to test decrement stock
            'image_path'=>'hewhispers.jpg',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('products')->insert([
            //Bibles 
            'category_id'=> $biblesId,
            'prod_name'=> 'She Reads Truth Bible',
            'prod_description'=> '',
            'price'=> 20.15,
            'stock_quantity'=> 10,
            'image_path'=>'shereads.jpg',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('products')->insert([
            //Journal & supplies 
            'category_id'=> $journalsSuppliesId,
            'prod_name'=> 'Gel Pens',
            'prod_description'=> 'Gel pens highlighters',
            'price'=> 4.20,
            'stock_quantity'=> 35,
            'image_path'=>'gelpens.jpg',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('products')->insert([
            //Decor 
            'category_id'=>$decorId,
            'prod_name'=> 'Christian Desk Decor', // https://www.amazon.com/Christian-Religious-Inspirational-Accessories-Decorations/dp/B0D8GY8PZX/ref=sr_1_26?dib=eyJ2IjoiMSJ9.EA_1B5MvblCwOpA3oHuoNImMDYY8jWupz6hLaZoYhXN-nVTC020V3PmT8dULg9ld-17X8X_9B_Kdb2H4jooQYkTjYiZFgsOq0Ipy49EptefCU_hLmsR_pij8hNQjWT-PfXVNNurCXaHOFnQTqCif_8jwa281RCLN-OmjkL-djuA5GqfefMv_G7euiIKRLyTDSZKyaHTCOPSCF0mmqp3zQoYQS82R8atKOhjoRPZ8t6iVQQhvKLTV2wTZuut7qGXWZygDPzcSwZj3AfW-u5Lii44Ik6mpV_eYCKXnM_yEYuc.IEQ_tEsJGSAXJhny4mrg45N65RRMGZjnWPHlNZTVKlM&dib_tag=se&keywords=christian%2Bdecor&qid=1763759944&sr=8-26&th=1
            'prod_description'=> ' 6 x Wooden Sign Material: This wooden sign is made of solid wood, sturdy and durable',
            'price'=> 9.00,
            'stock_quantity'=> 15,
            'image_path'=>'christiandeskdecor.jpg',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);


    }
}
    

