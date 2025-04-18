<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PendingTenant;
use Illuminate\Support\Facades\Log;

class TenantApproved
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Debug logging
            Log::info('Middleware check', [
                'is_admin' => session('is_admin'),
                'tenant_email' => session('tenant_email'),
                'tenant_id' => session('tenant_id'),
                'tenant_domain' => session('tenant_domain')
            ]);

            // Allow admin access
            if (session('is_admin')) {
                Log::info('Admin access granted');
                return $next($request);
            }

            $tenantEmail = session('tenant_email');
            
            if (!$tenantEmail) {
                Log::info('No tenant email found, redirecting to login');
                return redirect('/login')->with('error', 'Please login first.');
            }

            $tenant = PendingTenant::where('email', $tenantEmail)->first();
            Log::info('Tenant lookup in middleware', ['tenant' => $tenant ? $tenant->toArray() : null]);
            
            if (!$tenant) {
                Log::info('Tenant not found', ['email' => $tenantEmail]);
                return redirect('/login')->with('error', 'Tenant not found.');
            }

            if (!$tenant->approved) {
                Log::info('Tenant not approved', ['email' => $tenantEmail]);
                return redirect('/login')->with('error', 'Your account is not yet approved.');
            }

            Log::info('Tenant access granted', ['email' => $tenantEmail]);
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Middleware error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->with('error', 'An error occurred. Please try again.');
        }
    }
} 