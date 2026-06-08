<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Agar user login hai AUR uska role admin hai, to hi aage jaane do
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Warna error ke sath home page par bhej do
        return redirect('/')->with('error', 'Access Denied! Aap Admin nahi hain.');
    }
}