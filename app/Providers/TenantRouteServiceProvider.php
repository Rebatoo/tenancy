<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class TenantRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::middleware([
            'web',
            \Stancl\Tenancy\Middleware\InitializeTenancyByPath::class,
        ])->group(function () {
            $this->loadRoutesFrom(base_path('routes/tenant.php'));
        });
    }
} 