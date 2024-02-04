<?php

namespace App\Http\Middleware\FrontEnd;

use Closure;
use Illuminate\Http\Request;
use Auth;
class CheckPhoneVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()->phone_verified_at)
        {
            return redirect()->route('frontEnd.user.profile.home');
        }
        else
        {
            return $next($request);
        }
    }
}
