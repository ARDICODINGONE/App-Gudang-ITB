<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PetugasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login.show')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!in_array(auth()->user()->role, ['petugas', 'atasan'])) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk petugas atau admin.');
        }

        return $next($request);
    }
}
