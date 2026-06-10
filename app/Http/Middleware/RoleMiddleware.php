<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Cek apakah user sudah login dan punya role yang sesuai.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika belum login, arahkan ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Jika role tidak sesuai, redirect ke dashboard yang tepat
        if (auth()->user()->role !== $role) {
            if (auth()->user()->role === 'siswa') {
                return redirect()->route('dashboard.siswa')
                    ->with('error', 'Akses ditolak. Halaman tersebut hanya untuk bendahara.');
            }
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Halaman tersebut hanya untuk siswa.');
        }

        return $next($request);
    }
}
