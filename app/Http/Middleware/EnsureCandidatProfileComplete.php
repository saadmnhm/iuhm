<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCandidatProfileComplete
{
    /**
     * Block access to project/submission pages until the candidat profile is complete.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $candidat = Auth::guard('candidat')->user();

        if ($candidat && ! $candidat->isProfileComplete()) {
            return redirect()
                ->route('user.settings')
                ->with('profile_incomplete', true);
        }

        return $next($request);
    }
}
