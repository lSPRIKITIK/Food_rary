<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->position === 'Admin') {
            return $next($request); 
        }

        return redirect('/dashboard')->with('error', 'Access Denied: Admin privileges required.');
    }
}