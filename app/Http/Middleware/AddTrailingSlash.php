<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddTrailingSlash
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
        if (!$request->ajax() && !$request->isMethod('post')) 
        {
            $getWholeUrl = $request->getSchemeAndHttpHost() . $request->getRequestUri();
            
            // Check if the URL doesn't have a trailing slash
            if (substr($getWholeUrl, -1) !== '/') 
            {
                return redirect($getWholeUrl . '/', 301);
            }
        }
    
        return $next($request);
    }
}
