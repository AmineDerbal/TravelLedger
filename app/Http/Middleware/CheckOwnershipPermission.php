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
    public function handle(Request $request, Closure $next, string $permission): Response
    {
         $user = $request->user();

          if (!$user) {
            abort(403, 'Unauthorized');
        }

        $role = $user->roles()->first();

        if($role->name == 'admin') {
           return $next($request);
         }

         // Check for ownership permission
        $ownPermission = $permission . ' own';
        if ($user->hasPermissionTo($ownPermission) || $role->hasPermissionTo($permission)) {
            
        }

           abort(403, 'Forbidden - Missing permission: '.$permission);
    }
}