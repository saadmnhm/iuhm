<?php

namespace App\Http\Middleware;

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

        if (!$candidat->is_active) {
            Auth::guard('candidat')->logout();
            return redirect()->route('user.login')->withErrors([
                'login' => 'Your account has been disabled.'
            ]);
        }

        return $next($request);
    }
}
