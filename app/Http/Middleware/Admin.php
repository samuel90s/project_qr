<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'branch_admin') {
            // Admin atau branch_admin memiliki akses penuh
            return $next($request);
        }

        if ($user->role === 'user') {
            // Hanya perbolehkan user mengakses halaman produk untuk melihat
            if ($request->is('admin/products') || $request->is('admin/products/*')) {
                return $next($request);
            }

            // Jika user mengakses rute lain, arahkan ke halaman home
            return redirect('/');
        }

        // Jika bukan admin, branch_admin, atau user, redirect ke beranda
        return redirect('/');
    }
}
