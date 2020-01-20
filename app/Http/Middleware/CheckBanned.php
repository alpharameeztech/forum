<?php

namespace App\Http\Middleware;

use Closure;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( auth()->check() && auth()->user()->is_ban ) {

            \Log::info('User is identified as ban. Redirecting to login page with error message');
            auth()->logout();

            $message = 'Your account has been suspended. Please contact administrator.';

            return redirect()->route('login')->withMessage($message);
        }
        return $next($request);
    }
}
