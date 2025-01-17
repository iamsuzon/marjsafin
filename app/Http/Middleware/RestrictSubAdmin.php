<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictSubAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('admin')->user()->hasRole('sub-admin'))
        {
            $route_name = $request->route()->getName();

            $route = match ($route_name) {
                'admin.application.list' => 'admin.slip.list',
                default => 'admin.dashboard',
            };

            return redirect()->route($route);
        }

        return $next($request);
    }
}
