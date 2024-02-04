<?php

namespace App\Http\Controllers\NewAPI;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\LogErrorAndRedirectTrait;
use Carbon\Carbon;
use Exception;

class PostController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Post Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the Post apis in the mobile
    |
    */

    use LogErrorAndRedirectTrait;

    public function index()
    {
         /**
        * Index
        * 
        * Doc: get the posts for the user 
        *
        *
        * @return Json
        */

        try
        {
            $nowDate = Carbon::now()->format('Y-m-d H:i:s');

            $posts = Post::where('status',Post::PUBLISH)
            ->whereDate('date',"<=",$nowDate)
            ->whereHas('categories')
            ->orderBy('created_at','desc')
            ->limit(3)
            ->get()
            ->map(function ($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => $item->avatar,
                    'url' => route('frontEnd.blogs.details',['slug' => $item->slug]),
                ];
            });
    
            return $this->NewResponse(true, $posts , null , config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in getting the post: ');
        }
    }
}
