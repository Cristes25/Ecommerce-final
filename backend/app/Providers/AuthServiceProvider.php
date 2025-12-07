<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User; 


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        // Gate for admin users
        Gate::define('is-admin', function (User $user) {
            return $user->user_role === 'administrator';
        });

        // Optional: Gate for customer users
        Gate::define('is-customer', function (User $user) {
            return $user->user_role === 'customer';
        });
    }
}
