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
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('user.login')->withErrors([
                'email' => 'Your account has been disabled.'
            ]);
        }

        if (Auth::user()->role !== 'user') {
            Auth::logout();
            return redirect()->route('user.login')->withErrors([
                'email' => 'You are not authorized to access this area.'
            ]);
        }

        return $next($request);
    }
}
