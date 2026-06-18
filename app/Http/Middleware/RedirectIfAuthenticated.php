<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $user->load("roles");

                if ($user->hasAnyPermission(["dashboard.view", "customers.view", "work-orders.view"])) {
                    return redirect("/admin");
                }

                if ($user->hasRole("teknisi") || $user->technician) {
                    return redirect("/technician/dashboard");
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
