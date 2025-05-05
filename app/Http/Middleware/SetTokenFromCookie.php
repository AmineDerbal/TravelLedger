<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTokenFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
 
       if($request->hasCookie('access_token')){
        $request->headers->set('Authorization', 'Bearer '.$request->cookie('access_token'));
        return $next($request);
       }
       return response()->json(['message' => 'Unauthorized'], 401);
    }
}