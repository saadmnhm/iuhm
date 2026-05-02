<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CandidatMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('candidat')->check()) {
            return redirect()->route('user.login');
        }

        $candidat = Auth::guard('candidat')->user();

        if (Role::isDevelopmentAccessLocked()) {
            Auth::guard('candidat')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('user.login')->withErrors([
                'login' => 'Candidate access is temporarily disabled by development mode.',
            ]);
        }

        if (!$candidat->is_active) {
            Auth::guard('candidat')->logout();
            return redirect()->route('user.login')->withErrors([
                'login' => 'Your account has been disabled.'
            ]);
        }

        if (!$candidat->hasVerifiedEmail()) {
            return redirect()->route('user.verification.notice');
        }

        return $next($request);
    }
}
