<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrganizationAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('organization')->check()) {
            return redirect()->route('organization.login')->with('error', 'Please login to access organization panel.');
        }

        $organization = Auth::guard('organization')->user();
        
        if ($organization->status != 1) {
            Auth::guard('organization')->logout();
            return redirect()->route('organization.login')->with('error', 'Your organization account is inactive.');
        }

        return $next($request);
    }
}
