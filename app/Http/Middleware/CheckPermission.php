<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
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
        
        if($role->name == 'admin' || $role->hasPermissionTo($permission) || $user->hasPermissionTo($permission)) {
           return $next($request);
         }

         abort(403, 'Forbidden - Missing permission: '.$permission);

    }
}