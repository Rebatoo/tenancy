<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;
use App\Models\Tenant;

class InitializeTenancyByPath
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Extract tenant ID from the path
        $path = $request->path();
        $segments = explode('/', $path);
        
        if (count($segments) >= 2 && $segments[0] === 'tenant') {
            $tenantId = $segments[1];
            
            // Find the tenant
            $tenant = Tenant::where('id', $tenantId)->first();
            
            if ($tenant) {
                // Initialize tenancy for this tenant
                Tenancy::initialize($tenant);
                
                // Store tenant email in session for views
                session(['tenant_email' => $tenant->data['email'] ?? 'No email']);
            }
        }
        
        return $next($request);
    }
} 