<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class DashboardMiddleware
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

        if(!Auth::check()||Auth::user()->hasRole('Customer'))
        {
            return abort(403);
        }
        elseif(Auth::user()->is_deactivated)
        {
            Auth::logout();
            
            return redirect()->route('dashboard.login')->withErrors("Your Account was deactivated");
        }
        
        return $next($request);
    }
}
