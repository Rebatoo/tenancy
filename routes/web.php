<?php
use App\Http\Controllers\PendingTenantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
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
Route::middleware([TenantApproved::class])->group(function () {
    Route::get('/admin/pending-tenants', [PendingTenantController::class, 'index']);
    Route::post('/admin/approve-tenant/{id}', [PendingTenantController::class, 'approve'])->name('tenants.approve');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// Tenant routes
Route::prefix('tenant/{tenant}')->middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', [TenantController::class, 'showTenant'])->name('tenant.show');
    
    Route::middleware([TenantApproved::class])->group(function () {
        Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('tenant.dashboard');
        
        // Employee management routes
        Route::get('/employees', [EmployeeController::class, 'index'])->name('tenant.employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('tenant.employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('tenant.employees.store');
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('tenant.employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('tenant.employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('tenant.employees.destroy');
    });
});
