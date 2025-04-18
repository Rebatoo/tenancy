<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingTenant;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $email = $request->email;
            
            // Debug logging
            Log::info('Login attempt', ['email' => $email]);
        
            // Check if it's the admin
            if ($email === 'admin@gmail.com') {
                Log::info('Admin login attempt detected');
                session(['is_admin' => true]);
                Log::info('Admin session set', ['session' => session()->all()]);
                return redirect('/admin/pending-tenants');
            }
        
            // Check if it's a tenant
            $tenant = PendingTenant::where('email', $email)->first();
            Log::info('Tenant lookup result', ['tenant' => $tenant ? $tenant->toArray() : null]);

            if ($tenant) {
                if (!$tenant->approved) {
                    Log::info('Tenant not approved', ['email' => $email]);
                    return back()->with('error', 'Your account is not yet approved.');
                }
                
                // Store tenant info in session
                session([
                    'tenant_id' => $tenant->name,
                    'tenant_email' => $tenant->email,
                    'tenant_domain' => $tenant->domain
                ]);
                
                Log::info('Tenant session set', ['session' => session()->all()]);
                return redirect('/tenant/' . $tenant->name . '/dashboard');
            }
        
            Log::info('Invalid email attempt', ['email' => $email]);
            return back()->with('error', 'Invalid email');
        } catch (\Exception $e) {
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred during login. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        session()->forget(['tenant_id', 'tenant_email', 'tenant_domain', 'is_admin']);
        return redirect('/');
    }
}
