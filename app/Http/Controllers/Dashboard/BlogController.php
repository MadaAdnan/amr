<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PostStoreRequest;
use App\Http\Requests\Dashboard\SendRejectNoteRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBlogWithPublish;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\UtilitiesTrait;
use Carbon\Carbon;
use Exception , Auth , Alert;


class BlogController extends Controller
{

    use LogErrorAndRedirectTrait , JsonResponseTrait , UtilitiesTrait;

     /*
    |--------------------------------------------------------------------------
    | Blog Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all blogs action in the dashboard
    |
    */
    
    public function index(Request $request)
    {
        /**
        * Index
        * 
        * Doc: send the user to the blog page
        * 
        * @param \Illuminate\Http\Request $request will have filter queries and status
        *
        * @return \Illuminate\View\View
        * 
        */

        try
        {
            $status_array = config('general.show_correct_blogs_to_role');
            $roleName = Auth::user()->roleName;
            $query = $request->get('query');
            $status = $request->get('status');
            $nowDate = Carbon::now()->toDateString();
    
            #if the user was blogger show only he's blogs else show according to roles
            if($roleName == 'Blogger')
            {
                $data = Auth::user()->Blogger()->orderBy('updated_at','desc')->whereIn('status',$status_array['blogger']);
            }
            else
            {
                $data = Post::whereHas('user')->orderBy('updated_at','desc')->whereIn('status',$status_array[$roleName]);
            }
    
            #if the query exist preform search
            if($query)
            {
                $data = $data->where('title','like','%'.$query.'%');
            }
    
            #if the status is scheduled get the up coming data and the status should be published
            if($status&&$status == 'scheduled')
            {
    
               $data = $data->where('status','publish')
               ->whereDate('date',">",$nowDate);
            }
            elseif($status)
            {
                #if the status it's not "scheduled" return according to it and the publish where the publish date less than now
                $data = $data->where('status',$status);
    
                if($status == 'publish')
                {
                    $data = $data->whereDate('date',"<=",$nowDate);
                }
            }
            
            #get the data with pagination
            $data = $data->paginate(config('general.dashboard_pagination_number'));
    
            return view('dashboard.blogs.index',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error opening all blogs page dashboard: ');
        }
    }

    public function create()
    {
         /**
         * Create
         * 
         * Doc: send the user to the create page
         * 
         * @return \Illuminate\View\View
         */

         try
         {
            #tags and keywords are the same modal and data the only different is the is_keyword column 
            $keywords = Tag::where('is_keyword',Tag::IS_KEYWORD)->get();
            $tags = Tag::where('is_keyword',Tag::IS_TAGS)->get();

            return view('dashboard.blogs.create',compact('keywords','tags'));
         }
         catch(Exception $e)
         {
            return $this->logErrorAndRedirect($e,'Error opening create page dashboard: ');
         }

    }

    public function store(PostStoreRequest $request)
    {
         /**
         * Store
         * 
         * Doc: This will save the user info without necessary publishing it
         * 
         * @param \Illuminate\Http\Request $request will have the blog information
         * 
         * @return \Illuminate\Http\RedirectResponse
         */
        
        try
        {
            #create post
            $this->create_blog($request,'draft');

            #alert the user about the action happened
            Alert::toast('Blog was created','success');

            #redirect the user
            return redirect()->route('dashboard.blogs.index');

        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Error creating blog: ');
        }
    }

    public function check_slug(Request $request,$slug)
    {
         /**
         * Check Slug
         * 
         * Doc: To tell the user if the slug is available before sending the store request
         * 
         * @param \Illuminate\Http\Request $request will have the blog information
         * @return json
         */

        try
        {
            #create a new instance
            $data = new Post;
            $query = $request->get('my_slug');
           
            #if the query was sent that mean it's an update and don't count the coming slug as existing one
            if($query)
            {
               $data = $data->where('slug','!=',$query);
            }
            
            #search for the slug if it dose exist return true else false
            $data = $data->where('slug',$slug)->first();

            #return to the front end with updated status
            $responseObj = [
                'data'=>$data ? true : false ,
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
          return $this->logErrorJson($e,'Error checking the slug blog dashboard: ');
        }
    }

    public function pending(Request $request)
    {
        /**
         * Pending
         * 
         * Doc: Get pending blogs to the user than send it to the pending blogs view
         * 
         * @param \Illuminate\Http\Request $request will have the query if the user need to search
         * 
         * @return \Illuminate\View\View
         */

        try
        {
            $query = $request->get('query');
            $data = Post::whereHas('user')->orderBy('updated_at','desc')->where('status','Pending');
    
            #if the query exists preform an search
            if($query)
            {
                $data = $data->where('title','like','%'.$query.'%');
            }
    
            $data = $data->paginate(config('general.dashboard_pagination_number'));
    
            return view('dashboard.blogs.pending',compact('data'));
        }
        catch(Exception $e)
        {
           return $this->logErrorAndRedirect($e,'Error getting pending blogs: ');
        }

    }

    public function review($id)
    {
        /**
         * Review
         * 
         * Doc: show the blog to the admin to review
         * 
         * @param App\Models\Post $id blog post  id
         * 
         * @return \Illuminate\View\View
         * 
         */

         try
         {
            $data = Post::findOrFail($id);
            $keywords = Tag::get();

            #get the selected data so when the admin open the blade will already selected
            $selected_keywords = $data->keywords()->pluck('tags.id')->toArray();
            $selected_tags = $data->tags()->pluck('tags.id')->toArray();
            $selected_categories = $data->categories()->pluck('categories.id')->toArray();
    
            return view('dashboard.blogs.review',compact('data','keywords','selected_keywords','selected_tags','selected_categories'));
         }
         catch(Exception $e)
         {
           return $this->logErrorAndRedirect($e,'Error going to review the page: ');
         }

    }

    public function send_reject_note(SendRejectNoteRequest $request, $id,$status = 'rejected')
    {
        /**
         * Send Reject Note
         * 
         * Doc: send a reject note to the user.
         * 
         * @param \Illuminate\Http\Request $request will have the reject note
         * @param App\Models\Post blog post id
         * @param String $status sent status
         * @return Json
         * 
         */

        try
        {
            $data = Post::findOrFail($id);

            #update status
            $data->status = $status;
           
            #if the status reject it's mean from an blogger else mean it's from the admin
            if($status == 'rejected')
            {
                $data->reject_note = $request->reject_note;
            }
            else
            {
                $data->admin_reject_note = $request->reject_note;
            }

            #update data
            $data->save();
            
            Alert::toast('Note was saved','success');


            #return to the front end with updated status
            $responseObj = [
                'msg'=>'data was saved',
                'status'=>config('status_codes.success.ok')
            ];
            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e,'Blog id was not found in the send reject note dashboard: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error sending reject note: ');
        }
    }

    public function save_data(Request $request , $id)
    {
         /**
         * Save Data
         * 
         * Doc: save data as draft.
         * 
         * @param \Illuminate\Http\Request $request will have the blog post information
         * @param App\Models\Post $id blog post id
         * 
         * @return \Illuminate\Http\RedirectResponse
         * 
         */

        try
        {
            $data = Post::findOrFail($id);

            #update post information
            $this->update_blog($data,$request);
           
            return redirect()->route('dashboard.blogs.pending');

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e,'Blog id was not found in the save data dashboard: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error saving the data: ');
        }
    }

    public function edit($id)
    {
        /**
         * Edit
         * 
         * Doc: send the user to the edit page
         * 
         * @param App\Models\Post $id blog post id
         * 
         * @return \Illuminate\View\View
         */

         try
         {

            $data = Post::findOrFail($id);

            #tags and keywords are the same modal and data the only different is the is_keyword column 
            $keywords = Tag::where('is_keyword',1)->get();
            $tags = Tag::where('is_keyword',0)->get();

            #get the selected tags
            $selected_keywords = $data->keywords()->pluck('tags.id')->toArray();
            $selected_categories = $data->categories()->pluck('categories.id')->toArray();

            return view('dashboard.blogs.edit',compact('data','keywords','selected_keywords','selected_categories','tags'));

         }
         catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
         {
            return $this->logErrorJson($e,'Blog id was not found in edit page dashboard: ');
         }
         catch(Exception $e)
         {
            return $this->logErrorAndRedirect($e,'Error opening edit page dashboard: ');
         }

    }

    public function get_selected_data_select2($id)
    {
        /**
         * Get Selected Data Select 2 
         * 
         * Doc: send the user to the edit page
         * 
         * @param App\Models\Post $id blog post id
         * 
         * @return json
         */

        try
        {
            $data = Post::findOrFail($id);

            #get the selected data for the dashboard
            $selected_keywords = $data->keywords()->select('tags.id','tags.title')->get();
            $selected_categories = $data->categories()->select('categories.id','categories.title')->get();
            $selected_tags = $data->tags()->select('tags.id','tags.title')->get();


             #response object 
             $responseObj = [
                'data'=>[
                    'categories'=>$selected_categories,
                    'keywords'=>$selected_keywords,
                    'tags'=>$selected_tags
                ],
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           return $this->logErrorJson($e,'Blog id was not found in get select 2 data dashboard: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error getting data for select 2: ');
        }

    }

    public function update(Request $request,$id,$status = 'Draft')
    {
        /**
        * Update
        * 
        * Doc: Update the blog information 
        * 
        * @param \Illuminate\Http\Request $request will have the post information
        * @param App\Models\Post $id blog post
        * @param String  $status selected status if was not added select Draft
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        * 
        */

        try
        {
            #find the post
            $data = Post::findOrFail($id);

            #update
            $this->update_blog($data,$request,false,$status);

            #send an message
            Alert::toast('Blog was updated','success');

            return redirect()->route('dashboard.blogs.index');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
         {
            return $this->logErrorAndRedirect($e,'Error updating blog: ');
         }
        catch(Exception $e)
        {
            throw $e;
            return $this->logErrorAndRedirect($e,'Error creating blog: ');
        }
    }

    public function delete($id)
    {
        /**
        * Delete
        * 
        * Doc: Delete and remove the relations from the post 
        * 
        * @param App\Models\Post $id blog post
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        * 
        */

        try
        {
            $data = Post::findOrFail($id);

            #detach the relations from the modal and delete it
            $data->user()->dissociate();
            $data->categories()->detach();
            $data->tags()->detach();
            $data->keywords()->detach();
            $data->delete();

            Alert::toast('Data was deleted','success');

             #return to the front end with updated status
             $responseObj = [
                'msg'=>'data was deleted',
                'status'=>config('status_codes.success.deleted')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
          
          $this->logErrorJson($e,'Error finding blog post in delete blog post: ');

          return $this->errorResponse('Blog id was not found',config('status_codes.client_error.unprocessable'));

        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'Error deleting blog: ');

            return $this->errorResponse('Blog id was not found',config('status_codes.server_error.internal_error'));
  
        }
    }

    public function publish(Request $request ,$id)
    {
        /**
        * Publish
        * 
        * Doc: to publish the blog for the blogger so the admin can review it 
        * 
        * @param App\Models\Post $id blog post
        *
        *
        * @return \Illuminate\Http\RedirectResponse
        * 
        */

        try
        {
            #find post to update
            $data = Post::findOrFail($id);

            #update post
            $this->update_blog($data,$request,true,'Pending');

            #alert post was updated
            Alert::toast('Your blog is now under review','success');

            #redirect to index blogs
            return redirect()->route('dashboard.blogs.index');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorAndRedirect($e,'Provided id for the post: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Error publishing the blog: ');
        }

    }

    public function store_with_publish(StoreBlogWithPublish $request)
    {
        /**
        * Store With Publish
        * 
        * Doc: save the blog with publishing it 
        * 
        * @param \Illuminate\Http\Request
        *
        *
        * @return json
        * 
        */

        try
        {

            #get user
            $user = Auth::user();

            #select the publish status according to the user role
            if($user->roleName == 'Seo-admin'||$user->roleName == 'Super-admin')
            {
                $status = 'publish';
            }
            elseif($user->roleName == 'Seo-specialist'){
                $status = 'in-progress';
            }
            else
            {
                $status = 'pending';
            }

            #create blog
            $this->create_blog($request,$status);
            
            #show toast after the blogs was created
            Alert::toast('Blog was added','success');

            #response object 
            $responseObj = [
                'data'=>[],
                'status'=>config('status_codes.success.created')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.created'));

        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'Error creating blog in dashboard create with publish function: '); 
        }
    }

    public function release($id)
    {
         /**
        * Release
        * 
        * Doc: When the admin decide to release se the blog
        * 
        * @param \App\Models\Post $id
        *
        *  @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            #get the post and update the status to publish
            Post::findOrFail($id)->update(['status'=>'publish']);

            #send and toast to the client
            Alert::toast('Congrats Blog was published','success');

            return redirect()->route('dashboard.blogs.index');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorAndRedirect($e,'Provided id for the post: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Error publishing the blog: ');
        }

    }

    public function preview(Request $request, $id = null)
    {
        /**
        * Preview
        * 
        * Doc: If the user want to see how the blog look's on the website
        * 
        * @param \Illuminate\Http\Request $request 
        *
        *  @return \Illuminate\Http\RedirectResponse
        */
        
        try
        {
            #if the id was sent just update the date and show it to the user else create one
            if($id)
            {
                $data = Post::findOrFail($id);

                #if the status not equal pending update the blogs else just the relations with the image
                if($data->status != 'pending')
                {
                    $this->update_blog($data,$request,true);
                }
                else
                {
                    $data->categories()->sync($request->categories);
                    $data->tags()->sync($request->tags);
                    $data->keywords()->sync($request->keywords);

                    if($request->hasFile('image'))
                    {
                        $data
                        ->clearMediaCollection('images')
                        ->addMedia($request->file('image'))
                        ->toMediaCollection('images');
                    }
                }

            }
            else
            {
                $status = 'draft';
                $data = $this->create_blog($request,$status);
            }

            return view('dashboard.blogs.preview',compact('data'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            $this->logErrorJson($e,'Provided id for the post: ');

            return $this->errorResponse('Please provide correct post id',$e);
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error previewing the blog page: ');

            return back();
        }
    }

    private function create_blog( $request,$status)
    {
        /**
         * Create Blog
         * 
         * Doc: creating a new blog
         *
         * @param \Illuminate\Http\Request $request The request containing the update data.
         * @param string $status The status to set for the blog post.
         *
         * @return \App\Models\Post.
         */

        #get the logged in user
        $user = Auth::user();

        #generate slug if it was not sent
        $createSlug = $request->slug?str_replace(' ', '-', $request->slug):$this->generateRandomSlug(8);

        #collect the sent data
        $inputs = array_merge($request->all(),[
            'content'=>$request->content ?? '',
            'user_id'=>$user->id,
            'slug'=> $createSlug,
            'status'=>$status,
            'date'=>!$request->date?Carbon::now():$request->date
        ]);
        
        #create post
        $post = Post::create($inputs);

        #save the relation with the post
        $post->keywords()->sync($request->keywords);
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        #add an image if it dose exist
        if($request->hasFile('image'))
        {
            $post
            ->addMedia($request->file('image'))
            ->toMediaCollection('images');
        }

        return $post;
    }

    private function update_blog($post , $request , $updateSlug = true , $status = null)
    {
        /**
         * Update a blog post with the provided data.
         *
         * @param \App\Models\Post $post The blog post to update.
         * @param \Illuminate\Http\Request $request The request containing the update data.
         * @param bool $updateSlug Whether to update the slug.
         * @param string|null $status The status to set for the blog post.
         *
         * @return bool True if the update was successful, false otherwise.
         */

        #Format the provided slug or generate a random one
        $formattingSlug = $request->slug ? str_replace(' ', '-', $request->slug) : $this->generateRandomSlug(8);

        #Prepare the inputs for the update
        $inputs = array_merge($request->all(), [
            'content' => $request->content ?? 'add content...',
            'user_id' => Auth::user()->id,
        ]);

        #Update the slug if needed
        if ($updateSlug) {
            $inputs['slug'] = $formattingSlug;
        }

        #Update the status and date if provided
        if ($status) {
            $inputs['status'] = $post->status == 'publish' ? $post->status : $status;

            if ($status == 'publish') {
                $inputs['date'] = Carbon::now();
            }
        }

        #If the user is Seo-specialist or Blogger, only update the status
        if ($post->status != 'in-progress' && $post->status != 'publish' && (auth()->user()->roleName == 'Seo-specialist' || auth()->user()->roleName == 'Blogger')) {
            $inputs['status'] = $status;
        }

        #Update the blog image if provided
        if ($request->hasFile('image')) {
            $post
                ->clearMediaCollection('images')
                ->addMedia($request->file('image'))
                ->toMediaCollection('images');
        }

        #Update the blog post with the prepared inputs
        $post->update($inputs);

        #Sync the relations
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);
        $post->keywords()->sync($request->keywords);

        return true;
    }

}
