<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'admin' && $user->role !== 'admin') {
            // Redirect admin yang mencoba akses user area ke admin panel
            return redirect('/admin');
        }

        if ($role === 'user' && $user->role !== 'user') {
            // Redirect user yang mencoba akses admin area ke user area
            if ($user->role === 'admin') {
                return redirect('/admin');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
