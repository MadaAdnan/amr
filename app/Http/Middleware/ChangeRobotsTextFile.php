<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ChangeRobotsTextFile
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
        $domain = request()->getHost();
        $destroyUrl = explode('.',$domain);
        if ($destroyUrl[0] == 'blog') {
            $content = "User-agent: *\n";
            $content .= "Allow: /\n";
            $content .= "Disallow: /auth/login\n";
            $content .= "Sitemap: https://blog.lavishride.com/sitemap.xml\n";
        } else {
      
            $content = "User-agent: *\n";
            $content .= "Allow: /\n";
            $content .= "Disallow: /auth/login\n";
            // $content .= "Sitemap: https://lavishride.com/sitemap.xml\n";
            $content .= "Sitemap: https://blog.lavishride.com/sitemap.xml\n";
        }


       $file = public_path('robot.txt');
       File::put($file, $content);

       return $next($request);

    }
}
