<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AffiliateCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = auth()->user();

            if ($user->role == 'affiliate' && $request->is('admin/*')) {
                return redirect('/affiliate');
            }

            if ($user->role !== 'affiliate' && $request->is('affiliate')) {
                return redirect('/admin');
            }
        }

        return $next($request);
    }
}
