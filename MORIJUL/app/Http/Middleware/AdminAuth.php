<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('admin')) {
            // Jika tidak ada session admin, redirect ke halaman login
            return redirect()->route('login');
        }

        return $next($request);
    }

}


