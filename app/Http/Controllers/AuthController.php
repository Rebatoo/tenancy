<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingTenant;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->email;
    
        // Check if it's the admin
        if ($email === 'admin@gmail.com') {
            session(['is_admin' => true]);
            return redirect('/admin/pending-tenants');
        }
    
        // Check if it's a tenant
        $tenant = PendingTenant::where('email', $email)->first();

        if ($tenant) {
            if (!$tenant->approved) {
                return back()->with('error', 'Your account is not yet approved.');
            }
            
            // Store tenant info in session
            session([
                'tenant_id' => $tenant->name,
                'tenant_email' => $tenant->email,
                'tenant_domain' => $tenant->domain
            ]);
            
            return redirect('/tenant/' . $tenant->name . '/dashboard');
        }
    
        return back()->with('error', 'Invalid email');
    }

    public function logout(Request $request)
    {
        session()->forget(['tenant_id', 'tenant_email', 'tenant_domain', 'is_admin']);
        return redirect('/');
    }
}
