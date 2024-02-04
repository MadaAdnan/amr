<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveGoogleAnalyticsParameters
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
    
        $url = $request->url();
        
        // Remove specific query parameters
        $url = $this->removeQueryParameters($url, ['_gl', '_gcl_au', '_ga']);

        // Update the request URL
        $request->server->set('REQUEST_URI', $url);

        return $next($request);
    }

    protected function removeQueryParameters($url, $parameters)
    {
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);

            // Remove specified parameters
            $queryParams = array_diff_key($queryParams, array_flip($parameters));

            // Rebuild the query string
            $parsedUrl['query'] = http_build_query($queryParams);

            // Rebuild the full URL
            $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
            if (!empty($parsedUrl['query'])) {
                $url .= '?' . $parsedUrl['query'];
            }
            if (!empty($parsedUrl['fragment'])) {
                $url .= '#' . $parsedUrl['fragment'];
            }
        }

        return $url;
    }
}
