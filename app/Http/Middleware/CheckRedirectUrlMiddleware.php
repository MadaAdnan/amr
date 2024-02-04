<?php

namespace App\Http\Middleware;

use App\Models\RedirectMapping;
use Illuminate\Http\Request;

use Closure;

class CheckRedirectUrlMiddleware
{
    /**
     * Check Redirect Url Middleware.
     * 
     * doc: this will redirect the user to different url.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $data = RedirectMapping::get();
        $currentUrl = request()->getSchemeAndHttpHost().request()->getRequestUri();
        $dashboardLink = env('APP_URL').'/dashboard/broken-links';
        
        foreach($data as $item)
        {
            if($currentUrl == $item->old_url&&$currentUrl !== $dashboardLink)
            {
                $redirectUrl = $item->new_url;
                return response(view('redirectPage',compact('redirectUrl')));
            }
        }
        return $next($request);
    }
}
