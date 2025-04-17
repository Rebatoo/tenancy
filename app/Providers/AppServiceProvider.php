<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    public static function redirectTo()
{
    $user = auth()->user();

    if ($user->email === 'admin@gmail.com') {
        return '/admin/pending-tenants';
    }

    return '/dashboard'; // default for tenants
}
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
