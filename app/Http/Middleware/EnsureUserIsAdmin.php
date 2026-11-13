<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Halaman admin hanya untuk akun dengan role admin (dicek dari database lewat sesi). */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }
        if (! $request->user()->isAdmin()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
