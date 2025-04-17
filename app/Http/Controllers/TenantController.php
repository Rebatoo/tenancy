<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\PendingTenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function showTenant($tenant)
    {
        $tenant = Tenant::where('id', $tenant)->firstOrFail();
        $tenantInfo = PendingTenant::where('name', $tenant->id)->first();
        
        return view('tenant.show', compact('tenant', 'tenantInfo'));
    }

    public function dashboard($tenant)
    {
        $tenant = Tenant::where('id', $tenant)->firstOrFail();
        $tenantInfo = PendingTenant::where('name', $tenant->id)->first();
        
        return view('tenant.dashboard', compact('tenant', 'tenantInfo'));
    }
}
