<?php

namespace App\Http\Controllers;
use App\Models\PendingTenant;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log; // add this at the top
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PendingTenantController extends Controller
{
    public function create()
    {
        return view('register-tenant');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'location' => 'required',
            'domain' => 'required|unique:pending_tenants,domain',
            'contact_number' => 'required',
        ]);
    
        PendingTenant::create([
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'domain' => $request->domain,
            'contact_number' => $request->contact_number,
            'approved' => false,
        ]);
    
        return back()->with('success', 'Tenant registration submitted!');
    }
    public function index()
{
    $pendingTenants = PendingTenant::where('approved', false)->get();
    return view('admin.pending-tenants', compact('pendingTenants'));
}

public function approve($id)
{
    try {
        $pending = PendingTenant::findOrFail($id);

        // Check if tenant already exists
        if (Tenant::where('id', $pending->name)->exists()) {
            return back()->with('error', 'Tenant already exists!');
        }

        // Create the tenant
        $tenant = Tenant::create([
            'id' => $pending->name,
            'tenancy_db_name' => 'tenant_' . strtolower($pending->name),
        ]);

        // Create the domain
        $tenant->domains()->create([
            'domain' => $pending->domain,
        ]);

        // Mark as approved
        $pending->approved = true;
        $pending->save();

        return back()->with('success', 'Tenant approved!');
    } catch (\Exception $e) {
        Log::error('Approval error: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong during approval!');
    }
}

}