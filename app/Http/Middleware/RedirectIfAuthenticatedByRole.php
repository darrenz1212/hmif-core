<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedByRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            switch ($role) {
                case 'kalab':
                    return redirect()->route('kalab.dashboard');
                case 'hmif':
                    return redirect()->route('hmif.dashboard');
                case 'stafflab':
                    return redirect()->route('stafflab.dashboard');
                default:
                    return redirect('/');
            }
        }

        return $next($request);
    }
}
