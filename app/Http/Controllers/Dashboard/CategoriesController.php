<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\UtilitiesTrait;
use Exception, Alert;

class CategoriesController extends Controller
{
    use LogErrorAndRedirectTrait , JsonResponseTrait,UtilitiesTrait;

     /*
    |--------------------------------------------------------------------------
    | Categories Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all categories action in the dashboard
    |
    */

    public function index()
    {
        /**
        * Index
        * 
        * Doc: send the user to the categories page
        * 
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Category::orderBy('created_at','desc')->get()->take(config('general.categories_max_limit'));

            return view('dashboard.category.index',compact('data'));
        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Error sending the user to categories page: ');
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        /**
         * Store
         * 
         * Doc: This will save the categories data
         * 
         * @param \Illuminate\Http\Request $request will have the category information
         * 
         * @return Json
         */

        
        try
        {
            #count categories to check if it's above the max or not
            $categories = Category::count();
            if($categories >= config('general.categories_max_limit'))
            {
                 #return to the front end with updated status
                 $errMsg = "You can't add more than ".config('general.categories_max_limit')." categories";
                 return $this->errorResponse($errMsg,config('status_codes.client_error.unprocessable'));
            }

            #create category
            $data = Category::create($request->all());

            #return to the front-end with a message
            Alert::toast('Category was created','success');

             #return to the front end with updated status
             $responseObj  =[
                'id'=>$data->id,
                'text'=>$data->text,
                'status'=>config('status_codes.success.created')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.created'));

        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,'Error sending reject note: ');
        }
    }

    public function update(CategoryUpdateRequest $request,$id)
    {
        /**
        * Update
        * 
        * Doc: Update the category information 
        * 
        * @param \Illuminate\Http\Request $request will have the category information
        * @param App\Models\Category $id category
        *
        *
        * @return Json
        * 
        */
        
        try
        {
            #find category
            $data = Category::findOrFail($id);

            #check Slug
            $check = Category::where([['slug',$request->slug],['id','!=',$id]])->first();

            if($check)
            {
                 #return to the front end with updated status
                 $errMsg = "Slug already exist";
                 return $this->errorResponse($errMsg,config('status_codes.client_error.unprocessable'));
            }

            $data->update($request->all());

            Alert::toast('category was updated','success');

            #response msg
            $responseMsg = 'data was created';
            
            return $this->successResponse($responseMsg,config('status_codes.success.created'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           return $this->logErrorJson($e,'Category id was not found dashboard: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Creating category went wrong dashboard: ');
        }
    }

    public function get_categories_select(Request $request)
    {
         /**
         * Get Selected Data Select 2 
         * 
         * Doc: get categories for select 2
         * 
         * @param \Illuminate\Http\Request $request will have the query to filter
         * 
         * @return json
         */

        try
        {
            #get the search query from the request
            $query = $request->query('q');

            #initialize the category query, selecting only the 'id' and 'title' columns
            $data = Category::select('id','title');

            #search if the query dose exist
            if($query)
            {
                $data = $data->where('title','like','%'.$query.'%');
            }

            #get the categories
            $data = $data->get()->map(function($item){
                return[
                    'id'=>$item->id,
                    'text'=>$item->title,
                    'type'=>'public'
                ];
            });

            #return to the front end with updated status
            $responseObj  =[
                'data'=>$data,
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Creating category went wrong dashboard: ');
        }

    }

    public function pending()
    {
         /**
        * Pending
        * 
        * Doc: get pending categories
        * 
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Category::where('status',Category::STATUS_ACTIVE)->paginate(config('general.dashboard_pagination_number'));
            return view('dashboard.category.pending',compact('data'));
        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Error sending the user to categories page: ');
        }

    }

    public function delete($id)
    {
        /**
        * Delete
        * 
        * Doc: Delete and remove the relations from the categories 
        * 
        * @param App\Models\Category $id blog post
        *
        *
        * @return Json
        * 
        */

        try
        {
            $data = Category::findOrFail($id);
            $data->posts()->sync([]);
            $data->delete();
            Alert::toast('Category was deleted','success');

             #return to the front end with updated status
             $responseObj  =[
                'msg'=>'data was deleted',
                'status'=>config('status_codes.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.deleted'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           return $this->logErrorJson($e,'Category id in delete was not found dashboard: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error deleting the category: ');
        }
    }

}
