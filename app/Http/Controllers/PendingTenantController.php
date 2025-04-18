<?php

namespace App\Http\Controllers;

use App\Models\PendingTenant;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan; // Add this import
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;
use Stancl\Tenancy\Database\DatabaseManager;
use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager;
use App\Services\TenantService;

class PendingTenantController extends Controller
{
    protected $databaseManager;
    protected $tenantService;

    public function __construct(TenantDatabaseManager $databaseManager, TenantService $tenantService)
    {
        $this->databaseManager = $databaseManager;
        $this->tenantService = $tenantService;
    }

    public function create()
    {
        return view('register-tenant');
    }

    public function store(Request $request)
    {
        try {
            Log::info('Tenant registration attempt', ['data' => $request->all()]);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:pending_tenants,email',
                'location' => 'required|string|max:255',
                'domain' => 'required|string|unique:pending_tenants,domain',
                'contact_number' => 'required|string|max:255',
            ]);

            $tenant = PendingTenant::create([
                'name' => $request->name,
                'email' => $request->email,
                'location' => $request->location,
                'domain' => $request->domain,
                'contact_number' => $request->contact_number,
                'approved' => false,
            ]);

            Log::info('Tenant registered successfully', ['tenant' => $tenant->toArray()]);
            return back()->with('success', 'Tenant registration submitted!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error during tenant registration', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error during tenant registration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred during registration. Please try again.');
        }
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

            Log::info('Starting tenant approval process', [
                'pending_id' => $id,
                'tenant_name' => $pending->name,
            ]);

            // Approve the tenant using TenantService
            $this->tenantService->approveTenant($pending);

            // Mark as approved
            $pending->approved = true;
            $pending->save();

            Log::info('Tenant approved', [
                'tenant_id' => $pending->id,
            ]);

            return back()->with('success', 'Tenant approved!');
        } catch (\Exception $e) {
            Log::error('Approval error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong during approval! Error: ' . $e->getMessage());
        }
    }

    /**
     * Create a database manually using raw SQL.
     *
     * @param string $dbName
     * @return void
     * @throws \Exception
     */
    protected function createDatabaseManually($dbName)
    {
        try {
            DB::statement("CREATE DATABASE `$dbName`");
        } catch (\Exception $e) {
            Log::error('Error creating database manually: ' . $e->getMessage());
            throw new \Exception('Failed to create database: ' . $e->getMessage());
        }
    }
}