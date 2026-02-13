<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if 2FA is enabled
            if ($user->hasTwoFactorEnabled()) {
                session(['two_factor_required' => true]);
                session()->forget('two_factor_verified');
                
                return response()->json([
                    'success' => true,
                    'redirect' => route('auth.two-factor.verify')
                ]);
            }
            
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard.index')
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('two_factor_verified');
        session()->forget('two_factor_required');

        return redirect()->route('login');
    }
}

     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if 2FA is enabled
            if ($user->hasTwoFactorEnabled()) {
                session(['two_factor_required' => true]);
                session()->forget('two_factor_verified');
                
                return response()->json([
                    'success' => true,
                    'redirect' => route('auth.two-factor.verify')
                ]);
            }
            
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard.index')
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('two_factor_verified');
        session()->forget('two_factor_required');

        return redirect()->route('login');
    }
}
