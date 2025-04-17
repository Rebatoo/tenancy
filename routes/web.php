<?php
use App\Http\Controllers\PendingTenantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AuthController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Middleware\TenantApproved;

// Central domain routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/register-tenant', [PendingTenantController::class, 'create']);
Route::post('/register-tenant', [PendingTenantController::class, 'store']);

// Admin routes
Route::middleware(['auth', TenantApproved::class])->group(function () {
    Route::get('/admin/pending-tenants', [PendingTenantController::class, 'index']);
    Route::post('/admin/approve-tenant/{id}', [PendingTenantController::class, 'approve'])->name('tenants.approve');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Tenant routes
Route::prefix('tenant/{tenant}')->group(function () {
    Route::get('/', [TenantController::class, 'showTenant'])->name('tenant.show');
    Route::middleware(['auth', TenantApproved::class])->group(function () {
        Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('tenant.dashboard');
    });
});
