<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleApiNotFound
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
        $request_prefix = $request->segments();
        $response = $next($request);

        if(!empty($request_prefix[0]) &&  $request_prefix[0]== 'api'){
            if ($response->getStatusCode() === 404 ) {
                // Customize the JSON response for API not found here
                return response()->json([
                    'status' => false,
                    'results' => '',
                    'error' => 'API not found.'
                ], 404);
            }

            if ($response->getStatusCode() === 405 ) {
                // Customize the JSON response for API not found here
                return response()->json([
                    'status' => false,
                    'results' => '',
                    'error' => 'You are not presently authorized to use this system API.'
                ], 405);
            }

        }

        return $response;
    }
}
