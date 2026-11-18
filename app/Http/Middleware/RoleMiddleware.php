<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Mendukung pemanggilan format:
     * - 'role:admin,kasir'
     * - ['role:admin', 'role:kasir']
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Normalisasi jika argumen dipisah koma dalam 1 string
        $allowed = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $trimmed = trim($r);
                if ($trimmed !== '') {
                    $allowed[] = $trimmed;
                }
            }
        }

        if (! in_array($user->role, $allowed, true)) {
            // Jika kasir mencoba mengakses halaman khusus admin, arahkan ke POS
            if ($user->role === 'kasir') {
                return redirect()->route('pos.index')
                    ->with('error', 'Halaman tersebut hanya dapat diakses oleh Admin.');
            }

            abort(403, 'Akses tidak diizinkan untuk peran Anda.');
        }

        return $next($request);
    }
}
