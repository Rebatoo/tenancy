<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TenantDatabaseManager::class, function ($app) {
            $manager = new MySQLDatabaseManager();
            $manager->setConnection(config('database.default')); // Pass the connection name as a string
            return $manager;
        });
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
