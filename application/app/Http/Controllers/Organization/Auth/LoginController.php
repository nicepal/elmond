<?php

namespace App\Http\Controllers\Organization\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the organization login form.
     */
    public function showLoginForm()
    {
        $pageTitle = 'Organization Login';
        return view('organization.auth.login', compact('pageTitle'));
    }

    /**
     * Handle organization login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $organization = Organization::where('email', $request->email)->first();

        if (!$organization) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if ($organization->status != 1) {
            throw ValidationException::withMessages([
                'email' => ['Your organization account is inactive. Please contact administrator.'],
            ]);
        }

        if (!Hash::check($request->password, $organization->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        Auth::guard('organization')->login($organization, $request->filled('remember'));

        return redirect()->intended(route('organization.employees.index'))
                        ->with('success', 'Welcome back to your organization panel!');
    }

    /**
     * Handle organization logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('organization')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('organization.login')
                        ->with('success', 'You have been logged out successfully.');
    }
}
