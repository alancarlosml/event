<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('participante')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('participante')->user();

        if (!$user->hasRole('super_admin')) {
            abort(403, 'Acesso negado. Você precisa ser um super administrador.');
        }

        return $next($request);
    }
}

