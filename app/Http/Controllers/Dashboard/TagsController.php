<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Imports\KeywordsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;

class TagsController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Tags Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the tags actions in the dashboard
    |
    */

    use LogErrorAndRedirectTrait , JsonResponseTrait;

    public function index(Request $request)
    {
        /**
        * Index
        * 
        * Doc: go to index 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #init data
            $data = Tag::where('is_keyword',Tag::IS_KEYWORD)
            ->orderBy('created_at','desc');
    
            #Filter 
            $query = $request->get('query');
            $typeQuery = $request->get('type');
            $fromQuery = $request->get('from');
            $toQuery = $request->get('to');
    
            if($query)
            {
                $data = $data->where('title','like','%'.$query.'%')
                ->orWhere('strength','LIKE','%'.$query.'%')
                ->orWhere('monthly_volume','LIKE','%'.$query.'%')
                ->orWhere('subject','LIKE','%'.$query.'%'); 
            }
            
            #date filter
            if($fromQuery)
            {
                $data->whereBetween($request->type, [$fromQuery, $toQuery]);
            }
            
            $data = $data->paginate(config('general.dashboard_pagination_number'));
            $data->appends(request()->query());
            return view('dashboard.keywords.index',compact('data','query','typeQuery','fromQuery','toQuery'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting tags: ');
            return back();
        }
    }

    public function store(TagStoreRequest $request)
    {
        /**
        * store
        * 
        * Doc: save the tags
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $checkTitle = Tag::where('title',$request->title)->first();

            if($checkTitle)
            {
                return $this->response('make sure the title are unique',config('status_codes.client_error.unprocessable'));
            }

            #remove the spaces from the title to create a slug
            $slug = str_replace(" ", "-", $request->title);
            
            #get the tag data
            $request_data = array_merge($request->all(),[
                'slug'=> $slug,
                'subject'=> $request->subject ?? 0,
                'is_keyword'=> Tag::IS_KEYWORD
            ]);

            $data = Tag::create($request_data);
            
             #return to the front end with updated status if there is an links with the same info return false
             $responseObj = [
                'msg'=>'data was created',
                'data'=>$data,
                'status'=>config('status_codes.success.ok')
             ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch(Exception $e)
        {
           return $this->logErrorJson($e,'Error storing the data');
        }
    }

    public function update(TagUpdateRequest $request,$id)
    {
        /**
        * Update
        * 
        * Doc: update the tags
        *
        * @param Illuminate\Http\Request $request
        * @param Integer App\Models\Tag $id
        *
        * @return Json
        */

        try
        {
            $data = Tag::findOrFail($id);

            /** Check Slug */
            if($data)
            {
                $checkSlug = Tag::where([
                    ['slug',$request->slug],
                    ['id','!=',$id],
                    ['is_keyword',Tag::IS_KEYWORD]
                    ])
                    ->first();

                if($checkSlug)
                {
                    return $this->response('make sure the slug and the keyword are unique',config('status_codes.client_error.forbidden'));
                }
            }
            
            $data->update($request->all());
            
            $response = 'data was created';

            return $this->successResponse($response,config('status_codes.success.ok'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorAndRedirect($e,'Error in finding tag: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error in creating the tag');
        }
    }

    public function get_tags_select(Request $request)
    {
        /**
        * Get tags select
        * 
        * Doc: get the tags for select 2
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            #filter according to query
            $query = $request->query('q');

            $data = Tag::select('id','title','is_keyword')
            ->where('is_keyword',Tag::IS_TAGS);

            #if query was sent filter
            if($query)
            {
                $data = $data->where('title','like','%'.$query.'%');
            }

            #get the tags with this format for the select 2
            $data = $data->get()->map(function($item){
                return
                [
                    'id'=>$item->id,
                    'text'=>$item->title,
                    'type'=>'public'
                ];
            });

             #response json
             $responseObj = [
                'data'=>$data,
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch(Exception $e) 
        {
            return $this->logErrorJson($e,'Error in getting tags in select');
        }

    }

    public function delete($id)
    {
        /**
        * Delete
        * 
        * Doc: delete tags
        *
        * @param Integer App\Models\Tag $id
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get tags and remove the relations than delete it
            $data = Tag::findOrFail($id);
            $data->posts()->sync([]);
            $data->post_keywords()->sync([]);
            $data->delete();


             #response json
             $responseObj = [
                'data'=>$data,
                'status'=>config('status_codes.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.deleted'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e,'Error in finding tag: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error deleting tag: ');
        }

    }

    public function export_excel(Request $request)
    {
        /**
        * Export Excel
        * 
        * Doc: export keywords
        *
        *
        * @return \Maatwebsite\Excel\Facades\Excel
        */

        try
        { 
             #init data
             $data = Tag::where('is_keyword',Tag::IS_KEYWORD)
             ->orderBy('created_at','desc');
     
             #Filter 
             $query = $request->get('query');
             $typeQuery = $request->get('type');
             $fromQuery = $request->get('from');
             $toQuery = $request->get('to');
     
             if($query)
             {
                 $data = $data->where('title','like','%'.$query.'%')
                 ->orWhere('strength','LIKE','%'.$query.'%')
                 ->orWhere('monthly_volume','LIKE','%'.$query.'%')
                 ->orWhere('subject','LIKE','%'.$query.'%'); 
             }
             
             #date filter
             if($fromQuery)
             {
                 $data->whereBetween($request->type, [$fromQuery, $toQuery]);
             }
             
             $data = $data->get();
            

            
            return Excel::download(new KeywordsImport($data), 'keywords.xlsx');
        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Log error and redirect');
        }

       
    }

    public function import_keywords(Request $request)
    {
          /**
        * Import Excel keywords
        * 
        * Doc: export keywords
        *
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $path = $request->file('file');
            $data = Excel::toArray([], $path);
            $rows = $data[0];
            $array = [];

            foreach($rows as $index => $item)
            {
                $cols =  array_filter($item);
               if($index != 0&&count(array_filter($cols)) == 4)
               {
                    #get cols of the excel
                    $checkName = isset($cols[0]);
                    $checkSubject = isset($cols[1]);
                    $checkSubjectIsString = is_string($cols[1]);
                    $checkStrenthIsNumberBetween = is_numeric($cols[2]);
                    $checkLessThanTen = $cols[2] <= 10;
                    $checkMonthlyValIsNumber = is_numeric($cols[3]);
    
                    #make sure all the necessary cols are available
                    if(!$checkName||!$checkSubject||!$checkSubjectIsString||!$checkMonthlyValIsNumber||!$checkStrenthIsNumberBetween||!$checkLessThanTen)
                    {
                        return response()->json([
                            'msg'=>'An error occurred, kindly check your keywords sheet and follow its rules.',
                            'status'=>422
                        ],422);
                    }

                    #if the tags dose't exist create one
                    $word = Tag::where('title',$cols[0])->first();

                    if(!$word)
                    {
    
                        $slug = str_replace(" ", "-", $cols[0]);
                        $word =  Tag::create([
                            'title'=>$cols[0],
                            'slug'=>$slug,
                            'subject'=>$cols[1],
                            'strength'=>intval($cols[2]),
                            'monthly_volume'=>intval($cols[3]),
                        ]);
                    }
    
                    array_push($array,[
                        'id'=>$word->id,
                        'title'=>$word->title
                    ]);
               }
            }

            return back();

        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Log error and redirect');
        }
    }

    private function response($msg,$status = 200,$data = null)
    {
        /**
        * Response
        * 
        * Doc: json response for the api in this controller
        *
        * @param String $msg
        * @param Integer $status
        *
        * @return Json
        */


        return response()->json([
            'msg'=>$msg,
            'data'=>$data,
            'status'=>$status
        ],$status);
    }

    public function get_tags(Request $request)
    {
         /**
        * Get tags
        * 
        * Doc: get tags 
        *
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Tag::where('is_keyword',Tag::IS_TAGS)
            ->orderBy('created_at','desc');
    
            #filter data
            $query = $request->get('query');
            if($query)
            {
                $data = $data->where('title','like','%'.$query.'%');
            }
    
            
            $data = $data->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.tags.index',compact('data'));
        }
        catch(Exception $e)
        {
             $this->logErrorAndRedirect($e,'Error in getting tags in select');
             return back();
        }
      

    }

    public function tag_store(TagStoreRequest $request)
    {
          /**
        * Tag Store
        * 
        * Doc: store tag
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            #create a slug
            $slug = str_replace(" ", "-", $request->title);

            #get the data and save it in the database
            $request_data = array_merge($request->all(),[
                'slug'=>$request->slug??$slug,
                'is_keyword'=>0
            ]);
            #check if the slug available or not
            $checkTitle = Tag::where('title',$request->title)->first();
            if($checkTitle) return $this->response('make sure the title are unique',config('status_codes.success.ok'));

            #save data
            $data = Tag::create($request_data);

            return $this->response('data was created',config('status_codes.success.ok'),[
                'id'=>$data->id,
                'text'=>$data->text
            ]);

        }catch(Exception $e)
        {
            return $this->response('creating tag went wrong',config('status_codes.server_error.internal_error'));
        }
    }

    public function tag_update(TagUpdateRequest $request,$id)
    {
          /**
        * Tag Update
        * 
        * Doc: update the tag
        *
        * @param Integer App\Models\Tag $id
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */

        try
        {
            $data = Tag::findOrFail($id);

           #check slug
            if($data)
            {
                $checkSlug = Tag::where([['slug',$request->slug],['id','!=',$id],['is_keyword',Tag::IS_TAGS]])->first();
                $checkKeyword = Tag::where([['title',$request->title],['id','!=',$id],['is_keyword',Tag::IS_TAGS]])->first();

                if($checkSlug&&$checkKeyword)
                {
                    return $this->response('make sure the slug and the keyword are unique',config('status_codes.client_error.forbidden'));
                }
            }

            $data->update($request->all());

            return $this->response('Tag was updated');

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e,'Error in finding tag: ');
        }
        catch(Exception $e)
        {
            return $this->response('creating category went wrong',500);
        }
    }

    public function tag_delete($id)
    {
         /**
        * Delete
        * 
        * Doc: delete tags
        *
        * @param Integer App\Models\Tag $id
        *
        * @return \Illuminate\View\View
        */
        try
        {
            $data = Tag::findOrFail($id);
            
            #get tags and remove the relations than delete it
            $data->posts()->sync([]);
            $data->post_keywords()->sync([]);
            $data->delete();

               
            #response json
               $responseObj = [
                'data'=>$data,
                'status'=>config('status_codes.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.deleted'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e,'Error in finding tag: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error deleting tag: ');
        }

    }

    public function save_keywords(Request $request)
    {
         /**
        * Save Keywords
        * 
        * Doc: save keywords
        *
        * @param Illuminate\Http\Request $request
        *
        * @return Json
        */
        try
        {
            $selectedData = [];

            #loop throw the keywords and return the selected one 
            foreach ($request->items as $key => $value) {
               $data = Tag::where([['title',$value],['is_keyword',1]])->first();
               
               if(!$data)
               {
                $slug = str_replace(" ", "-", $value).rand(10,100000);
                $data = Tag::create([
                    'title'=>$value,
                    'slug'=>$slug,
                    'is_keyword'=>1
                ]);
               }

               array_push($selectedData,[
                'id'=>$data->id,
                'value'=>$data->title
               ]);
            }

            return response()->json([
                'msg'=>'Data was updated',
                'data'=>[
                    'items'=>$selectedData
                ]
            ],config('status_codes.success.ok'));

        }catch(Exception $e)
        {
            return response()->json([
                'err'=>'Error saving the keywords'
            ],config('status_codes.server_error.internal_error'));
        }
    }

    

}
