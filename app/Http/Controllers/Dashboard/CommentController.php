<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CommentActionRequest;
use App\Http\Requests\Dashboard\ReplyCommentRequest;
use App\Models\Comment;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Illuminate\Http\Request;
use Auth;
use Exception;

class CommentController extends Controller
{

     /*
    |--------------------------------------------------------------------------
    | Comment Controller
    |--------------------------------------------------------------------------
    |
    | Responsible for all comments action in the dashboard
    |
    */

    use LogErrorAndRedirectTrait, JsonResponseTrait;

    public function index(Request $request)
    {
        /**
        * Index
        * 
        * Doc: send the user to comments page in the dashboard and do search query and filtering if it's was sent
        * 
        * @param Illuminate\Http\Request $request to get the filter information
        *
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #initiate the comment query
            $data = Comment::whereHas('posts')
            ->orderBy('created_at','desc');

            $query = $request->get('query');
            $status = $request->get('status');
    
            #if the query exist search according to query
            if($query)
            {
                $data = $data->where('text','like','%'.$query.'%')
                ->orWhere('email','like','%'.$query.'%')
                ->orWhere('name','like','%'.$query.'%');
            }
            
            #if the if the status exist filter according to the status
            if($status)
            {
                $data = $data->where('status',$status);
            }
    
    
            #get the comment according to the query above with pagination than return the view
            $data = $data->paginate(config('general.dashboard_pagination_number'));

            return view('dashboard.comments.index',compact('data'));
        }
        catch (Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error viewing comments index: ');
            return back();
        }
        
    }

    public function show($id,$goToPreview = false)
    {
        /**
        * Show
        * 
        * Doc: get the comment information
        * 
        * @param Number $id the id of App\Models\Comment
        * @param Boolean $goToPreview to go the preview page of the comments
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get the comment than go to the view page
            $data = Comment::whereHas('posts')->findOrFail($id);

            #if the goToPreview is true go to the preview page else go to the show page
            if($goToPreview)
            {
                return view('blog.preview',compact('data'));
            }
            else
            {
                return view('dashboard.comments.show',compact('data'));
            }
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'error in finding the comments: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error in showing the comments: ');
            return back();
        }
    }

    public function commentAction(CommentActionRequest $request,$id)
    {
         /**
        * Comment Action
        * 
        * Doc: to update the comment status
        * 
        * @param Illuminate\Http\Request $request to get the filter information
        *
        * @param Integer $id the id of App\Models\Comment
        *
        *
        * @return Json
        */

        try
        {
            #find comment to update
            $data = Comment::findOrFail($id);
            $data->update([
                'status'=>$request->status
            ]);

             #return to the front end with updated status
             $responseObj  = $this->format_response('Comment status was changed',config('status_codes.success.updated'),$data->id);

             return $this->successResponse($responseObj,config('status_codes.success.updated'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorJson($e,'error in find comment in comment action: ');
        }
        catch(Exception $e)
        {
            throw $e;
            $this->logErrorJson($e,'error in comment action: ');
        }
    }

    public function reply(ReplyCommentRequest $request,$id)
    {
        /**
        * Reply
        * 
        * Doc: replying to the comments
        * 
        * @param Illuminate\Http\Request $request to get the filter information
        *
        * @param Integer $id the id of App\Models\Comment
        *
        *
        * @return Json
        */

        try
        {
            #find the comment to add the replay to it
            $comment = Comment::findOrFail($id);

            #reply comment data
            $reply_data = [
                'text'=>$request->content,
                'user_id'=>Auth::user()->id,
                'comment_id'=>$comment->id,
                'post_id'=>$comment->posts->id,
            ];

            $reply = Comment::create($reply_data);

            #attach the reply to the comment
            $comment->comments()->save($reply);

            #return to the front end with updated status
            $responseObj  = $this->format_response('Reply was created',config('status_codes.success.created'),$reply->id);

            return $this->successResponse($responseObj,config('status_codes.success.created'));
    
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorJson($e,'error in find comment in reply: ');
        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'error in comment action: ');
        }

    }

    private function format_response($msg , $status, $comment_id)
    {
        /**
         * Format Response
         * 
         * Doc: formatting the response for the comment
         * 
         * @param String $msg the message will appear in the response
         * @param String $status status code will appear in the response
         * @param Integer $comment_id the comment id will be sent to the front-end
         */

        return [
            'msg'=>$msg,
            'data'=>[
                'id'=>$comment_id
            ],
            'status'=>$status
        ];
    }

    public function preview($id)
    {
       /**
        * Preview
        * 
        * Doc: View the comments on the Blog
        * 
        * @param Integer $id the id of the comment
        *
        * @param Integer $id the id of App\Models\Comment
        *
        *
        * @return \Illuminate\Contracts\View\View
        */

        try
        {
            $data = Comment::whereHas('posts')->findOrFail($id);
            return view('blog.preview',compact('data'));
        }
        catch(Exception $e)
        {
            return $this->logErrorAndRedirect($e,'Error in previewing the comment: ');
        }
    }

}
