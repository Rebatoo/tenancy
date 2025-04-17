<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PendingTenant;

class TenantApproved
{
    public function handle(Request $request, Closure $next)
    {
        // Allow admin access
        if (session('is_admin')) {
            return $next($request);
        }

        $tenantEmail = session('tenant_email');
        
        if (!$tenantEmail) {
            return redirect('/login');
        }

        $tenant = PendingTenant::where('email', $tenantEmail)->first();
        
        if (!$tenant) {
            return redirect('/login');
        }

        if (!$tenant->approved) {
            return redirect('/login')->with('error', 'Your account is not yet approved.');
        }

        return $next($request);
    }
} 