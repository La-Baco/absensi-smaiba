<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        if (Auth::check()) {
            $userRole = Auth::user()->role;
            $currentPath = $request->path(); // misalnya 'admin' atau 'operator'

            if ($userRole === 'admin' && $currentPath !== 'admin') {
                return redirect('/admin');
            } elseif ($userRole === 'operator' && $currentPath !== 'operator') {
                return redirect('/operator');
            }

            // Kalau sudah di halaman sesuai role tapi masih gagal, logout untuk mencegah loop
            Auth::logout();
            return redirect('/')->withErrors('Terjadi kesalahan akses.');
        }

        return redirect('/')->with('error', 'Kamu harus login terlebih dahulu.');
    }
}
