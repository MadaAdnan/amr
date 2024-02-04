<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Traits\LogErrorAndRedirectTrait;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BlogController extends Controller
{
      /*
    |--------------------------------------------------------------------------
    | Blog Controller
    |--------------------------------------------------------------------------
    |
    | This handles the showing of the services in the frontEnd 
    |
    */

    use LogErrorAndRedirectTrait;

    #query for the post 

    // Query for the post in the frontEnd
    private $shownQuery;

    public function __construct()
    {
        $this->shownQuery = [
            ['status', Post::PUBLISH],
            ['date', '<=', Carbon::now()],
        ];
    }



    public function index()
    {
        /**
         * Index
         * 
         * 
         * doc: going to the blog main page
         * 
         * @return View
         */

        try
        {
            $data = Post::where($this->shownQuery)->paginate(config('general.blog_front_end_pagination'));
            return view('frontEnd.blogs.index',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error entering the blog');
            return back();
        }
    }

    public function details($slug)
    {
         /**
         * Details
         * 
         * 
         * doc: go to the blogs details in the frontEnd
         * 
         * @param String $slug to get the blog by the slug
         * 
         * @return View
         */   

         try
         {
            $data = Post::where($this->shownQuery)
            ->where('slug',$slug)
            ->firstOrFail();

            return view('frontEnd.blogs.details',compact('data'));
         }
         catch (ModelNotFoundException $e) {
            // Log the error if needed
            $this->logErrorAndRedirect($e, 'Blog with slug "'.$slug.'" not found');
        
            // Redirect to a 404 page or any other fallback action
            abort(404);
        }
         catch(Exception $e)
         {
            //throw $e;
            $this->logErrorAndRedirect($e,'Error in getting the blogs details: ');
            return back();
         }
    }

    public function categories($slug)
    {
        /**
         * Categories
         * 
         * doc: Get blogs according to categories
         * 
         * @param String $slug to get the category was selected
         */

        try
        {
            $category = Category::where([['slug',$slug],['status',Category::STATUS_ACTIVE]])->firstOrFail();

            $data = $category->posts()->where($this->shownQuery)->paginate(config('general.blog_front_end_pagination'));
            
            return view('frontEnd.blogs.index',compact('data'));
 
        }
        catch(Exception $e)
        {
            throw $e;
            $this->logErrorAndRedirect($e,'Error in getting the blogs for the categories');
            return back();
        }
    }
}
