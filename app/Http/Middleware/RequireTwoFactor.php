<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Skip 2FA check for 2FA routes and logout
        if ($request->routeIs('auth.two-factor.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // If 2FA is enabled but not verified in this session
        if ($user->hasTwoFactorEnabled() && !session('two_factor_verified')) {
            session(['two_factor_required' => true]);
            return redirect()->route('auth.two-factor.verify');
        }

        return $next($request);
    }
}

        }

        // Skip 2FA check for 2FA routes and logout
        if ($request->routeIs('auth.two-factor.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // If 2FA is enabled but not verified in this session
        if ($user->hasTwoFactorEnabled() && !session('two_factor_verified')) {
            session(['two_factor_required' => true]);
            return redirect()->route('auth.two-factor.verify');
        }

        return $next($request);
    }
}
