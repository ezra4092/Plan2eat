<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Memastikan pengguna yang terautentikasi memiliki salah satu role yang diizinkan.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login_page');
        }

        $userRole = strtolower(trim((string) $user->role));
        $allowedRoles = array_map(
            fn (string $role): string => strtolower(trim($role)),
            $roles
        );

        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak untuk peran akun Anda.',
            ], 403);
        }

        $homeRoute = match ($userRole) {
            'umkm' => 'umkm.dashboard',
            'admin' => 'admin.dashboard',
            default => 'pelanggan.beranda',
        };

        return redirect()
            ->route($homeRoute)
            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
