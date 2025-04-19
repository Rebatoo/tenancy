<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Stancl\Tenancy\Middleware\IdentificationMiddleware;
use Stancl\Tenancy\Resolvers\PathTenantResolver;
use Stancl\Tenancy\Tenancy;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class InitializeTenancyByPath extends IdentificationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get the tenant ID from the route parameter
        $tenantId = $request->route('tenant');
        Log::info('Tenant ID from route', ['tenant_id' => $tenantId]);
        
        if (!$tenantId) {
            Log::error('No tenant ID found in route');
            abort(404, 'Tenant not found');
        }
        
        // Find the tenant by ID
        $tenant = Tenant::find($tenantId);
        Log::info('Found tenant', ['tenant' => $tenant ? $tenant->toArray() : null]);
        
        if (!$tenant) {
            Log::error('Tenant not found in database', ['tenant_id' => $tenantId]);
            abort(404, 'Tenant not found');
        }
        
        // Set the database name for the tenant connection
        $databaseName = 'tenant_' . $tenant->id;
        Log::info('Setting database name', ['database_name' => $databaseName]);
        Config::set('database.connections.tenant.database', $databaseName);
        
        // Initialize tenancy
        try {
            tenancy()->initialize($tenant);
            Log::info('Tenancy initialized successfully');
        } catch (\Exception $e) {
            Log::error('Error initializing tenancy', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Error initializing tenant');
        }
        
        return $next($request);
    }
} 