<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use App\Models\AppSettings;
use App\Models\Category;
use App\Models\Comment;
use App\Models\NavPages;
use App\Models\Page;
use App\Models\Post;
use App\Models\Services;
use App\Models\SliderServices;
use Illuminate\Pagination\Paginator;

class CommonDataMiddleware
{
    /**
     * CommonDataMiddleware
     * 
     * doc: to share variables
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        #variables
        $nowDate = Carbon::now()->format('Y-m-d H:i:s');
        
        #get data to run the frontEnd
        $services = Services::where([
            ['status',Services::STATUS_PUBLISHED],
            ['is_orphan',0],
            ['slug','!=',null]
        ])
        ->orderBy('position','asc')
        ->get()
        ->take(config('general.services_items_nav_bar'));

        #get categories
        $categories = Category::where('status',Category::STATUS_ACTIVE)
        ->get()
        ->take(config('general.categories_items_nav_bar'));



        #shared variables
        view()->share([
            'services' => $services,
            'categories'=>$categories
        ]);

        return $next($request);
    }
}
