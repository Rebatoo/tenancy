<?php

namespace App\Http\Controllers;

use Stancl\Tenancy\Facades\Tenancy;
use App\Models\Tenant;
use App\Models\PendingTenant;
use Illuminate\Http\Request;
use App\Services\TenantService;

class TenantController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    // Display tenant details
    public function showTenant($tenant)
    {
        $tenant = Tenant::where('id', $tenant)->firstOrFail();
        $tenantInfo = PendingTenant::where('name', $tenant->id)->first();
        
        return view('tenant.show', compact('tenant', 'tenantInfo'));
    }

    // Tenant dashboard
    public function dashboard($tenant)
    {
        $tenant = Tenant::where('id', $tenant)->firstOrFail();
        $tenantInfo = PendingTenant::where('name', $tenant->id)->first();
        
        return view('tenant.dashboard', compact('tenant', 'tenantInfo'));
    }

    // Method to create a new tenant
    public function createTenant(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'tenant_id' => 'required|string|unique:tenants,id', // Validating tenant_id
        ]);

        // Create a new Tenant instance
        $tenant = new Tenant();
        $tenant->id = $request->tenant_id; // Set the tenant ID
        $tenant->data = [
            'tenancy_db_name' => 'tenant_' . $request->tenant_id, // Set the dynamic tenant database name
        ];

        // Save the tenant instance
        $tenant->save();

        // Create the database for the tenant
        $tenant->createDatabase();

        // Optionally, run migrations for the tenant's database
        $tenant->runMigrations();

        return response()->json([
            'message' => 'Tenant created and database initialized.',
            'tenant' => $tenant,
        ]);
    }

    public function approve($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        try {
            $this->tenantService->approveTenant($tenant);
            return response()->json(['message' => 'Tenant approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->tenantService->registerTenant($request->all());
            return response()->json(['message' => 'Tenant registered successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong during approval!', 'details' => $e->getMessage()], 500);
        }
    }
}
