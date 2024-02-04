<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Exception;
use Alert;
use App\Http\Requests\AboutUsRequest;
use App\Http\Requests\Dashboard\HomePageRequest;
use App\Http\Requests\Dashboard\TermsRequest;
use App\Traits\LogErrorAndRedirectTrait;

class SettingsController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Settings Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the settings actions in the dashboard
    |
    */

    use LogErrorAndRedirectTrait;

    public function store_home_page(HomePageRequest $request)
    {
         /**
        * Store home page
        * 
        * Doc: the data in home page
        *
        * @param Illuminate\Http\Request $request
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $inputs = [
                'text'=>'Home',
                'value'=>json_encode([
                    'what_makes_us_title'=>$request->what_makes_us_title,
                    'what_makes_us_description'=>$request->what_makes_us_description,
                    'icon_what_makes_us_one_title'=>$request->icon_what_makes_us_one_title,
                    'icon_what_makes_us_one_description'=>$request->icon_what_makes_us_one_description,
                    'icon_what_makes_us_two_title'=>$request->icon_what_makes_us_two_title,
                    'icon_what_makes_us_two_description'=>$request->icon_what_makes_us_two_description,
                    'icon_what_makes_us_three_title'=>$request->icon_what_makes_us_three_title,
                    'icon_what_makes_us_three_description'=>$request->icon_what_makes_us_three_description,
                    'icon_what_makes_us_four_title'=>$request->icon_what_makes_us_four_title,
                    'icon_what_makes_us_four_description'=>$request->icon_what_makes_us_four_description,
                    'icon_what_makes_us_five_title'=>$request->icon_what_makes_us_five_title,
                    'icon_what_makes_us_five_description'=>$request->icon_what_makes_us_five_description,
                    'icon_what_makes_us_six_title'=>$request->icon_what_makes_us_six_title,
                    'icon_what_makes_us_six_description'=>$request->icon_what_makes_us_six_description,
                    'title_about_us'=>$request->title_about_us,
                    'description_about_us'=>$request->description_about_us,
                    'title_our_services'=>$request->title_our_services,
                    'description_our_services'=>$request->description_our_services,
                    'alt_image_one'=>$request->alt_image_one,
                    'image_one_title'=>$request->image_one_title,
                    'image_one_description'=>$request->image_one_description,
                    'image_two_title'=>$request->image_two_title,
                    'image_two_description'=>$request->image_two_description,
                    'alt_image_two'=>$request->alt_image_two,
                ])
            ];
            $data = AppSettings::where('text','home')->updateOrCreate(['text'=>'home'],$inputs);

            if($request->hasFile('icon_one'))
            {
                $data
                ->clearMediaCollection('what_makes_us_icon_one')
                ->addMedia($request->file('icon_one'))
                ->toMediaCollection('what_makes_us_icon_one');
            }
            if($request->hasFile('icon_two'))
            {
                $data
                ->clearMediaCollection('what_makes_us_icon_two')
                ->addMedia($request->file('icon_two'))
                ->toMediaCollection('what_makes_us_icon_two');
            }
            if($request->hasFile('icon_three'))
            {
                $data
                ->clearMediaCollection('what_makes_us_icon_three')
                ->addMedia($request->file('icon_three'))
                ->toMediaCollection('what_makes_us_icon_three');
            }
            if($request->hasFile('icon_four'))
            {
                $data
                ->clearMediaCollection('what_makes_us_icon_four')
                ->addMedia($request->file('icon_four'))
                ->toMediaCollection('what_makes_us_icon_four');
            }
            if($request->hasFile('icon_five'))
            {
                $data
                ->clearMediaCollection('what_makes_us_icon_five')
                ->addMedia($request->file('icon_five'))
                ->toMediaCollection('what_makes_us_icon_five');
            }
            if($request->hasFile('icon_six'))
            {
                $data
                ->clearMediaCollection('what_makes_us_icon_six')
                ->addMedia($request->file('icon_six'))
                ->toMediaCollection('what_makes_us_icon_six');
            }
          
            if($request->hasFile('image_one'))
            {
                $data
                ->clearMediaCollection('home_page_slider_image_one')
                ->addMedia($request->file('image_one'))
                ->toMediaCollection('home_page_slider_image_one');
            }
    
            if($request->hasFile('image_two'))
            {
                $data
                ->clearMediaCollection('home_page_slider_image_two')
                ->addMedia($request->file('image_two'))
                ->toMediaCollection('home_page_slider_image_two');
            }
           
            Alert::toast('Data was updated','success');
            return back();

        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'store page home error');
            return back();
        }
        
    }

    public function store_about_us_page(AboutUsRequest $request)
    {
        /**
        * Store about us page
        * 
        * Doc: store home about page content.
        *
        * @param Illuminate\Http\Request $request
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            $inputs = [
                'text'=>'About',
                'value'=>json_encode([
                    'section_one_title'=>$request->section_one_title,
                    'section_one_description'=>$request->section_one_description,
                    'section_one_paragraph_one_title'=>$request->section_one_paragraph_one_title,
                    'section_one_paragraph_one_description'=>$request->section_one_paragraph_one_description,
                    'section_one_paragraph_two_title'=>$request->section_one_paragraph_two_title,
                    'section_one_paragraph_two_description'=>$request->section_one_paragraph_two_description,
                    'section_two_title'=>$request->section_two_title,
                    'section_two_description'=>$request->section_two_description,
                    'section_two_paragraph_one_title'=>$request->section_two_paragraph_one_title,
                    'section_two_paragraph_one_description'=>$request->section_two_paragraph_one_description,
                    'section_two_paragraph_two_title'=>$request->section_two_paragraph_two_title,
                    'section_two_paragraph_two_description'=>$request->section_two_paragraph_two_description,
                    'section_three_title'=>$request->section_three_title,
                    'section_three_description'=>$request->section_three_description,
                ])
            ];

            AppSettings::where('text','About')->updateOrCreate(['text'=>'About'],$inputs);

            Alert::toast('About us page was updated','success');
            return back();

        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error store about us');
            return back();
        }
    }

    public function store_terms(TermsRequest $request)
    {
         /**
        * Store terms
        * 
        * Doc: store the terms for the front.
        *
        * @param Illuminate\Http\Request $request
        *
        *
        * @return \Illuminate\View\View
        */

        try
        {
            AppSettings::where('text','Terms')->update([
                'value'=>[
                    'terms'=>$request->terms,
                    'policy'=>$request->policy
                ]
            ]);
            Alert::toast('Data was updated','success');
            return back();
        }
        catch(Exception $e)
        {
            $this->logErrorAndRedirect($e,'error store terms');
            return back();

        }
    }

   
}
