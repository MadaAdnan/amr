<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Analytics;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;
use Spatie\Analytics\Period;

class HomeController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | to show the main page in the dashboard
    |
    */

    use LogErrorAndRedirectTrait;

    public function index()
    {
         /**
        * Index
        * 
        * Doc: main dashboard page 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $date_now = Carbon::now();

            #get google analytics information  
           $viewsLastWeekDate = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7))->map(function($item){
                return $item['date']->format('d/D');
            })->toArray();
    
            $viewsLastWeekVisitor = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7))->map(function($item){
                return $item['visitors'];
            })->toArray();
    
            $topReference = Analytics::fetchTopReferrers(Period::days(7))->map(function($item){
                return $item;
            })->toArray();
    
            $totalSessions = Analytics::fetchTopBrowsers(Period::days(7),4)->sum('sessions');
            $topBrowsersNames = Analytics::fetchTopBrowsers(Period::days(7),4)->map(function($item){
                return $item['browser'];
            })->toArray();
            $topBrowsersSessions = Analytics::fetchTopBrowsers(Period::days(7),4)->map(function($item){
                return $item['sessions'];
            })->toArray();
            
    
            $response = Analytics::performQuery(
                Period::days(7),
                'ga:bounceRate,ga:sessions,ga:sessionDuration,ga:users',
                [
                    'dimensions' => 'ga:date'
                ]
            );
    
            #put google data in an object
            $rows = $response['rows'];
            $googleData = (object)[];
            foreach ($rows as $row) {
                $googleData->date = $row[0];
                $googleData->bounceRate = $row[1];
                $googleData->sessions = $row[2];
                $googleData->sessionDuration = $row[3];
                $googleData->users = $row[4];
            }
        
            #get coming posts to show in the calender
            $blogs = Post::whereDate('date','>=',$date_now)->get()->map(function($item){
                return[
                    'title'=>$item->title,
                    'date'=>$item->date->format('Y-m-d H:i:s')
                ];
            });

            return view('dashboard.index',compact('blogs','viewsLastWeekDate','viewsLastWeekVisitor','googleData','topBrowsersSessions','topBrowsersNames'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting data in main page dashboard: ');
            return back();
        }
    }

    public function upload_an_Image_ck_editor(Request $request)
    {
          /**
        * Upload an Image ck editor
        * 
        * Doc: to upload an image to the ck editor
        *
        *
        * @return void
        */

        if($request->hasFile('upload')) 
        {
            #get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();
            
            #get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            
            #get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();
            
            #filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;
       
            #Upload File
            $request->file('upload')->storeAs('public/uploads', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/uploads/'.$filenametostore);
            $msg = 'Image successfully uploaded';
            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
              

            #Render HTML output
            // @header('Content-type: text/html; charset=utf-8');
            return response($re)->header('Content-Type', 'text/html; charset=utf-8');

        }

    }
}
