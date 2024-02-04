<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Models\NavPages;
use App\Models\Page;
use App\Models\SeoCountry;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Alert;
use App\Http\Requests\Dashboard\SendRejectNoteRequest;
use App\Http\Requests\StoreBlogWithPublish;
use App\Models\AppSettings;
use App\Models\Faq;
use App\Models\Fleet;
use App\Models\FleetCategory;
use App\Models\Services;
use App\Models\SliderServices;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use Auth;


class PageController extends Controller
{

     /*
    |--------------------------------------------------------------------------
    | Page Controller
    |--------------------------------------------------------------------------
    |
    | user all users action in the dashboard
    |
    */

    use LogErrorAndRedirectTrait,JsonResponseTrait;

    public function index(Request $request,$type)
    {
         /**
        * Index
        * 
        * Doc: get pages for webpages sections 
        *
        * @param String $type the page type
        * @param Illuminate\Http\Request $request
        *
        * @return \Illuminate\View\View
        */
        
        try
        {
            
            $data = Page::orderBy('updated_at','desc')
            ->where('status',"!=",Page::STATUS_PENDING)
            ->whereHas('navSections',function($q) use($type){
                $q->where('title',$type);
            });
    
            /** Initiate the arrays for the view  */
            $services = [];
            $fleetCategories = [];
            $selectFleetCategory = [];
            $fleets = [];
            $services = [];
            $seo_countries = [];
            $faqType = $request->query('faqType') ?? 'General';
            $pageData = null;
            $content =[];
    
            #search for the selected settings
            $settingModal = AppSettings::where('text',$type)->first();

            if($settingModal)
            {
                $pageData = json_decode($settingModal->value);
            }

            #get the service sliders
            $services = SliderServices::orderBy('created_at','desc')->get();
            
    
            #get the data according to the type
            if($type == 'Fleet Category')
            {
                #get the fleet categories
                $selectFleetCategory = FleetCategory::orderBy('created_at','desc')
                ->get();
            }
            if($type == 'Fleet')
            {
                $fleets = Fleet::orderBy('created_at','desc')
                ->paginate(config('general.dashboard_pagination_number'));
                $jsonString  = AppSettings::where('text','Fleet')->first();
                $content = json_decode($jsonString->value, true);
               
            }
            if($type == 'Services')
            {
                $services = Services::orderBy('created_at','desc')
                ->get();
            }
            if($type == 'Faq')
            {
                $services = Faq::orderBy('sort','asc');
                
                if($faqType)
                {
                    if($faqType == 'Cancellations') $faqType = 'Cancellations & Refunds';
                    $services = $services->where('type',$faqType);
                }
                
                $services = $services->get();
            }
            if($type == 'Countries')
            {
                $seo_countries = SeoCountry::orderBy('created_at','desc')->paginate(config('general.dashboard_pagination_number'));
                $jsonString  = AppSettings::where('text','Countries')->first();
                $content = json_decode($jsonString->value, true);
                
            }
            
            
            $status = $request->get('status');
            $navs = NavPages::get();
            
    
            $data = $data->paginate(config('general.dashboard_pagination_number'));
            
            return view('dashboard.pages.index',compact('data','content','navs','pageData','settingModal','services','selectFleetCategory','fleets','seo_countries'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting pages in index: ');
            return back(); 
        }
    }

    public function create()
    {
        /**
        * Create
        * 
        * Doc: send the user to the create page 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $keywords = Tag::get();
            $navPages = NavPages::get();

            return view('dashboard.pages.create',compact('keywords','navPages'));    
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error getting pages in index: ');
            return back(); 
        }
    }

    public function edit($id)
    {
         /**
        * Edit
        * 
        * Doc: send the user to the edit page 
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get page info
            $data = Page::findOrFail($id);

            #get the tags
            $keywords = Tag::get();
            $navPages = NavPages::get();
            $selected_keywords = $data->keywords()
            ->pluck('tags.id')
            ->toArray();

            return view('dashboard.pages.edit',compact('data','keywords','navPages','selected_keywords'));    
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the page dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
           $this->logErrorAndRedirect($e,'Error page in dashboard: ');
           return back();
        }
    }

    public function pending(Request $request)
    {
        /**
        * Pending
        * 
        * Doc: get the pending pages
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Page::where('status',Page::STATUS_PENDING)
            ->orderBy('updated_at','desc');

            #filtering
            $query = $request->get('query');

            if($query)
            {
                $data = $data->where('title','like','%'.$query.'%');
            }
    
            $data = $data->paginate(config('general.dashboard_pagination_number'));
    
            return view('dashboard.pages.pending',compact('data'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the page dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
           $this->logErrorAndRedirect($e,'Error page in dashboard: ');
           return back();
        }
    }

    public function store(StorePageRequest $request)
    {
         /**
        * Store
        * 
        * Doc: store the page data 
        *
        * @param Illuminate\Http\Request $request will have the page controller information
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            #add the data to the request
            $inputs = array_merge($request->all(),[
                'content'=>json_encode($request->content),
                'status'=>Page::STATUS_DRAFT
            ]);
            $data = Page::create($inputs);

            #attach the relations
            $data->navSections()->associate($request->navPage);
            $data->keywords()->sync($request->keywords);
            $data->save();

            #add the image if it dose exist
            if($request->hasFile('image'))
            {
                $data->addMedia($request->file('image'))
                ->toMediaCollection('images');
            }

            Alert::toast('Page was created','success');
            return redirect()->route('dashboard.pages.index','Home');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error storing page: ');
            return back();
        }
    }

    public function update(StorePageRequest $request,$id)
    {
        /**
        * Update
        * 
        * Doc: update the page data 
        *
        * @param Integer $id App\Models\Page
        * @param Illuminate\Http\Request $request will have the page controller information
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            #find the data and update it
            $status = Auth::user()->roleName == 'Seo-admin'? Page::STATUS_PUBLISHED: Page::STATUS_DRAFT;
            $this->save_data($request,$status,$id);
            Alert::toast('Page was updated','success');

            return redirect()->route('dashboard.pages.index','Home');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the page dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
           $this->logErrorAndRedirect($e,'Error updating the page: ');
           return back();
        }
    }

    public function check_slug(Request $request,$slug)
    {
         /**
        * Check Slug
        * 
        * Doc: check the slug for the page 
        *
        * @param String $slug App\Models\Page
        * @param Illuminate\Http\Request $request will have the page controller information
        *
        * @return Json
        */

        try
        {
            #initiate the page modal 
            $data = new Page;
            $query = $request->get('my_slug');
           
            #if the slug was sent do not count it from the existing one
            if($query)
            {
               $data = $data->where('slug','!=',$query);
            }

            $data = $data->where('slug',$slug)->first();

             #return to the front end with updated status
             $responseObj = [
                'data'=>$data?true:false,
                'status'=>config('status_code.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.ok'));            
        }
        catch(Exception $e)
        {
            $this->logErrorJson($e,'Error checking the slug: ');
            return back();
        }
    }

    public function delete($id)
    {
         /**
        * Delete
        * 
        * Doc: delete the page and remove the relations
        *
        * @param Integer $id App\Models\Page
        * @param Illuminate\Http\Request $request will have the page controller information
        *
        * @return \Illuminate\View\View;
        */

        try
        {
            $data = Page::findOrFail($id);

            #remove the relations from the page
            $data->keywords()->sync([]);
            $data->delete();

            Alert::toast('Page was deleted','success');

            return back();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the page dashboard: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error Finding the page dashboard: ');
            return back();
        }
    }

    public function publish($id)
    {
         /**
        * Publish the page
        * 
        * Doc: delete the page and remove the relations
        *
        * @param Integer $id App\Models\Page
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            #update the status according to the role name
            $status = Auth::user()->roleName == 'Seo-admin'?'Published':'Pending';

            #update the user info
            Page::findOrFail($id)->update(['status'=>$status]);
            Alert::toast('Data was saved','success');
            return redirect()->route('dashboard.pages.index','Home');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the page dashboard publish: ');
           return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error in the publish the page: ');
            return back(); 
        }
    }

    public function review($id)
    {
         /**
        * Review
        * 
        * Doc: review the page by the admin
        *
        * @param Integer $id App\Models\Page
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $data = Page::findOrFail($id);
            $navPages = NavPages::get();
            $selected_navs = $data->navSections()->pluck('nav_pages.id')->toArray();
    
            return view('dashboard.pages.review',compact('data','selected_navs','navPages'));    
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the page dashboard publish: ');
           return back();
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           $this->logErrorAndRedirect($e,'Error Finding the page dashboard publish: ');
           return back();
        }
    }

    public function send_reject_note(SendRejectNoteRequest $request, $id)
    {
         /**
        * Send reject note
        * 
        * Doc: send the reject note by the admin
        *
        * @param Integer $id App\Models\Page
        *
        * @return \Illuminate\View\View
        */

        try
        {
            #get the data and update it
            $data = [
                'status'=>Page::STATUS_REJECTED,
                'reject_note'=>$request->reject_note
            ];

            Page::findOrFail($id)->update($data);

             #return to the front end with updated status
             $responseObj = [
                'msg'=>'data was saved',
                'status'=>config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj,config('status_codes.success.deleted'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
           return $this->logErrorJson($e,'Error Finding the page dashboard publish: ');
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error sending reject note: ');
        }
    }

    public function store_with_publish(StoreBlogWithPublish $request,$id = null)
    {
        /**
        * Store with publish
        * 
        * Doc: publish the blog
        *
        * @param Illuminate\Http\Request $request
        *
        * @param Integer $id App\Models\Page
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $status = Auth::user()->roleName == 'Seo-admin'?'Published':'Pending';

            if(!$id)
            {

                $this->save_data($request,$status);
            }
            else
            {
                $this->save_data($request,$status,$id);
            }

            Alert::toast('Data was updated','success');

            return back();

        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error Finding the page dashboard publish: ');
            return back(); 
        }
    }

    public function preview(Request $request , $id = null)
    {
        /**
        * Preview 
        * 
        * Doc: Preview the page and update or create according the id if it's null or not
        *
        * @param Integer $id App\Models\Page
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $status = 'Draft';

            if(!$id)
            {
                $data = $this->save_data($request,$status);
            }
            else
            {
                $data = $this->save_data($request,$status,$id);
            }

            return view('dashboard.pages.preview',compact('data'));
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error Preview page: ');
            return back(); 
        }

    }

    public function release(Request $request,$id)
    {
         /**
        * Release 
        * 
        * Doc: Release the page and change the status to publish
        *
        * @param Integer $id App\Models\Page
        *
        * @return \Illuminate\Http\RedirectResponse
        */

        try
        {
            #publish the page
            $status = Page::STATUS_PUBLISHED;
            $this->save_data($request,$status,$id);
            Alert::toast('Page was updated','success');

            return redirect()->route('dashboard.pages.pending','Home');
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'Error Finding the page dashboard publish: ');
            return back(); 
        }
    }
    
    private function save_data($request,$status,$id = null)
    {
        /**
        * Save data
        * 
        * Doc: to add or update the page 
        *
        * @param Illuminate\Http\Request $request
        *
        * @param Integer $id App\Models\Page
        * @param String $status
        *
        * @return \Illuminate\View\View
        */

        $inputs = array_merge($request->all(),[
            'content'=>json_encode($request->content),
            'status'=>$status
        ]);

        #check if id was sent or not if it's update it's info
        if($id)
        {
            $data = Page::findOrFail($id);
        }
        else
        {
            $data = Page::create($inputs);
        }
        
        #attach the relations
        $data->navSections()->associate($request->navPage);
        $data->keywords()->sync($request->keywords);

        $data->save();

        if($request->hasFile('image'))
        {
            $data
            ->clearMediaCollection('images')
            ->addMedia($request->file('image'))
            ->toMediaCollection('images');
        }
        if($type == 'Countries')
        {
            $inputs = [
                'text'=>'Countries',
                'value'=>json_encode([
                   
                ])
            ];
        }

        return $data;

    
    }

    public function createCountryContent(Request $request)
    {
        /**
        * Create Country Content
        * 
        * Doc: create the seo content for the seo 
        *
        * @param Illuminate\Http\Request $request
        *   
        * @return Json
        */

        try
        {

            $inputs = [
                'value' => json_encode([
                    'content' => $request->content,
                    'seoTitleValue' => $request->seoTitleValue,
                    'seoDescriptionValue' => $request->seoDescriptionValue,
                    'seoKeyPhraseValue' => $request->seoKeyPhraseValue,
                    'countrySeoHeader'=>$request->countrySeoHeader,
                ])];
       

            return AppSettings::updateOrCreate(['text' => 'Countries'],$inputs);
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'error in creating the country content');
        }
    }

    public function createFleetContent(Request $request)
    {

         /**
        * Create Fleet Content
        * 
        * Doc: create the seo content for the seo 
        *
        * @param Illuminate\Http\Request $request
        *   
        * @return Json
        */

        try
        {
            $inputs = [
                'value' => json_encode([
                    'content' => $request->content,
                    'fleetSeoHeader'=>$request->fleetSeoHeader,
                ])];
       

            return AppSettings::updateOrCreate(['text' => 'Fleet'],$inputs);
        }
        catch(Exception $e)
        {

        }
    }

}
