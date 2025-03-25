<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$request->user() || !$request->user()->role) {
            return redirect('/');
        }

        foreach ($roles as $role) {
            if ($request->user()->role->name === $role) {
                return $next($request);
            }
        }

        return redirect('/');
    }
}
