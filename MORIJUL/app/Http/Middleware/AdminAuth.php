<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (session('role') !== 'admin') {
            // Non-admin users get sent to delivery area
            return redirect()->route('delivery.dashboard');
        }
        return $next($request);
    }


}


