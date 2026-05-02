<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ModuleAccess
{
    /**
     * Check if the current user's role is allowed for the given module.
     * Usage in routes: ->middleware('module:rh')
     *
     * If the module is not defined in config/modules.php, access is granted to all admins.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!Auth::check()) {
            abort(403, "Vous n'avez pas accès à ce module.");
        }

        $user = Auth::user();

        if (Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($user->role)) {
            return $next($request);
        }

        // Always allow a user to view/edit their own profile,
        // even if they don't have the "users" module permission.
        if ($module === 'users') {
            $routeId = $request->route('id');
            if ($routeId && (int) $routeId === Auth::id()) {
                return $next($request);
            }
        }

        if (!Role::canAccess($user->role, $module)) {
            abort(403, "Vous n'avez pas accès au module : {$module}.");
        }

        return $next($request);
    }
}
