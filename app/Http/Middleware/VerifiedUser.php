<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->user_type !== 'manager' && !auth()->user()->is_verified) {
            info(auth()->user()->user_type);
            return redirect(route('otp.verifications'));
        }

        return $next($request);
    }
}
