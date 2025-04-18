<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PendingTenant;

class CheckTenantApproval
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->email === 'admin@gmail.com') {
            return redirect('/admin/pending-tenants');
        }

        $tenant = PendingTenant::where('email', $user->email)->first();

        if ($tenant && $tenant->approved) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Your account is not yet approved.');
    }
}
