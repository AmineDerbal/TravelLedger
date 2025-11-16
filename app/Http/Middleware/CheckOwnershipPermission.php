<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnershipPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $role = $user->roles()->first();

        if ($role->name == 'admin' && $role->hasPermissionTo($permission)) {
            return $next($request);
        }

        // Check for ownership permission
        $splitPermission = explode(' ', $permission);
        $splitPermission[0] = $splitPermission[0].'_own';
        $ownPermission = implode(' ', $splitPermission);

        if ($role->hasPermissionTo($ownPermission) && $user->id == $request->user_id) {
            return $next($request);
        }

        abort(403, 'Forbidden - Missing permission: '.$permission);
    }
}
