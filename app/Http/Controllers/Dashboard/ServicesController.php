<?php

namespace App\Http\Controllers\Dashboard;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\SeoCity;
use App\Models\Services;
use App\Models\ServicesSection;
use App\Traits\JsonResponseTrait;
use App\Traits\LogErrorAndRedirectTrait;
use App\Traits\UtilitiesTrait;
use Exception;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Services Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the services actions in the dashboard
    |
    */

    use JsonResponseTrait, LogErrorAndRedirectTrait, UtilitiesTrait;

    #these slugs you the user can't select because it's already been used
    protected $forbiddenSlugs = [
        'about-us',
        'fleet',
        'faqs',
        'affiliate',
        'we-are-hiring-a-licensed-chauffeur',
        'terms-and-conditions',
        'privacy-policy',
        'contact-us'
    ];

    public function storeCityId(Request $request)
    {
        /**
         * Store City ID
         * 
         * Doc: go to create city
         *
         *
         * @return \Illuminate\View\View
         */

        try 
        {
            return view('dashboard.services.create');
        } catch (Exception $e) {
            $this->logErrorAndRedirect($e, 'Error in create services: ');
            return back();
        }
    }

    public function create(Request $request)
    {
        try 
        {
            $city_id = session('selectedCityId');

            if (!$city_id) {
                $city_id = $request->query('city_id', null);
            }

            $cities = SeoCity::where('status', SeoCity::STATUS_ACTIVE)->get();
            
            return view('dashboard.services.create', compact('cities', 'city_id'));
        } 
        catch (Exception $e) 
        {
            Alert::toast('Error in reach create page', 'error');
            return back();
        }
    }

    public function edit($id)
    {
        /**
         * Edit
         * 
         * Doc: go to edit page
         *
         * @param Integer $id App\Models\Services
         *
         * @return \Illuminate\View\View
         */


        try {
            $data = Services::findOrFail($id);
            $cities = SeoCity::where('status', SeoCity::STATUS_ACTIVE)->get();

            return view('dashboard.services.edit', compact('data', 'cities'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logErrorAndRedirect($e, 'Error in finding service: ');
            return back();
        } catch (Exception $e) {
            $this->logErrorAndRedirect($e, 'Error in edit page: ');
            return back();
        }
    }

    public function delete($id)
    {
        /**
         * Delete
         * 
         * Doc: delete service
         *
         * @param Integer $id App\Models\Services
         *
         * @return \Illuminate\View\View
         */

        try {
            $data = Services::findOrFail($id);

            #delete the sections
            $data->sections()->delete();

            $data->delete();

            #return to the front end with updated status if there is an links with the same info return false
            $responseObj = [
                'msg' => 'data was deleted',
                'status' => config('status_codes.success.ok')
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } catch (Exception $e) {
            return $this->logErrorJson($e, 'Error deleting the service');
        }
    }

    public function store(Request $request)
    {
        /**
         * Store
         * 
         * Doc: store the service
         *
         * @param Illuminate\Http\Request
         *
         * @return \Illuminate\View\View
         */
        try {
            #data sent and create
            $data = array_merge($request->all(), [
                'is_orphan' => $request->isOrphan == '1' ? 1 : 0,
                'slug' => $this->generate_slug($request->slug),
                'icons_section' => json_encode([]),
                'status' => $request->is_preview == 1 ? Services::STATUS_DRAFT : Services::STATUS_PUBLISHED,
                'image_url' => $request->image_url,
                'position'=>0
            ]);

            $services = Services::create($data);

            if ($request->hasFile('image')) {
                $services->addMedia($request->image)
                    ->toMediaCollection('images');
            }

            #add sections to service
            foreach ($request->images as $key => $value) {
                $valueData = $value;
                
                $serviceData = array_merge($valueData, [
                    'is_left' => $valueData['isRight'] == 'false' ? 1 : 0,
                    'service_id' => $services->id,
                    'paragraph_image_url' => $valueData['urlParagraphImage']
                ]);


                $data = ServicesSection::create($serviceData);
                if ($valueData['urlParagraphImage'] == null) {
                    $data->addMedia($valueData['image'])
                        ->toMediaCollection('images');
                }
            }

            $responseObj = [
                'msg' => 'data was added',
                'data' => [
                    'is_preview' => $request->is_preview,
                    'service_slug' => $services->slug
                ],
                'status' => config('status_codes.ok')
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } catch (Exception $e) {
            throw $e;
            return $this->logErrorJson($e, 'error creating the services');
        }

    }

    public function update(Request $request, $id)
    {
        /**
         * Update
         * 
         * Doc: store the service
         *
         * @param Illuminate\Http\Request $request
         * @param Integer $id App\Models\Services
         *
         * @return \Illuminate\View\View
         */

        try 
        {
            #get the services
            $services = Services::findOrFail($id);

            #get input data for the service
            $data = $request->all();

            $services->update(array_merge($data,[
                'slug' => $this->generate_slug($request->slug),
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_key_phrase' => $request->seo_key_phrase,
                'is_orphan' => $request->isOrphan == '1' ? 1 : 0,
                'icons_section' => json_encode([]),
                'status' => $request->is_preview == 1 ? Services::STATUS_DRAFT : Services::STATUS_PUBLISHED,
                'paragraph_image_url' => $request->urlParagraphImage,
                'position'=>0,
                'city_id'=>gettype($request->city_id) == 'string' ? null : $request->city_id
            ]));

            #update the image for the service
            if ($request->hasFile('image')) {
                $services
                    ->clearMediaCollection('images')
                    ->addMedia($request->image)
                    ->toMediaCollection('images');
            }
           
            #delete selected sections
            if ($request->deleted_paragraphs) {
                foreach ($request->deleted_paragraphs as $deleted_id) 
                {
                    $section = ServicesSection::find($deleted_id);
                    if ($section)
                        $section->delete();
                }
            }

            #loop throw the images update the service section
            foreach ($request->images as $key => $value) {
                $valueData = $value;

                if ($valueData['ignore'] != 'true') {
                    $service_sections = array_merge($valueData, [
                        'is_left' => !array_key_exists('isRight',$valueData)||$valueData['isRight'] == 'false' ? 1 : 0,
                        'sort_number' => $valueData['sort'],
                        'service_id' => $services->id,
                        'paragraph_image_url'=>$valueData['urlParagraphImage']
                    ]);
                    $data = ServicesSection::create($service_sections);
                    
                    //This mean the image is url
                    if($valueData['image'] != 'undefined'&&$valueData['urlParagraphImage'] == null)
                    {
                        $data->addMedia($valueData['image'])
                            ->toMediaCollection('images');
                    }

                } 
                else 
                {
                    if (array_key_exists('id', $valueData)) {
                        $data = ServicesSection::find($valueData['id']);
                        if ($data) {
                            $data->update([
                                'sort_number' => $valueData['sort'],
                            ]);
                        }
                    }
                }
            }

            $responseObj = [
                'msg' => 'data was updated',
                'data' => [
                    'is_preview' => $request->is_preview,
                    'service_slug' => $services->slug
                ],
                'status' => config('status_codes.success.updated')
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));

        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return $this->logErrorJson($e, 'Error in finding service: '); 
        } 
        catch (Exception $e) 
        {
            throw $e;
            return $this->logErrorJson($e, 'Error in finding service: ');
        }

    }

    public function preview($slug)
    {
        /**
         * Preview
         * 
         * Doc: preview the services
         *
         * @param String $slug
         *
         * @return \Illuminate\View\View
         */

        try {
            $data = Services::where('slug', $slug)->firstOrFail();
            return view('frontEnd.services.preview', compact('data'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logErrorAndRedirect($e, 'Error finding the services: ');
            return back();
        } catch (Exception $e) {
            $this->logErrorAndRedirect($e, 'Error in finding service: ');
            return back();
        }

    }

    public function checkSlug($slug, $id = null)
    {
        /**
         * Check Slug
         * 
         * Doc: check slug available for creating
         *
         * @param String $slug
         * @param Integer $id App\Models\Services
         *
         * @return Json
         */

        try {
            $data = new Services;

            if ($id) {
                $data = $data->where('id', '!=', $id);
            }

            $data = $data->where('slug', '=', $slug)->first();

            #get response obj
            $responseObj = [
                'msg' => 'slug was checked',
                'data' => [
                    'is_available' => $data ? false : true
                ]
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));

        } catch (Exception $e) {
            return $this->logErrorJson($e, 'error in the slug');
        }
    }

    public function get_paragraph($id)
    {
        /**
         * Get paragraph
         * 
         * Doc: check slug available for creating
         *
         * @param String $slug
         * @param Integer $id App\Models\Services
         *
         * @return JSON
         */

        try {
            $data = ServicesSection::findOrFail($id);

            #get response obj
            $responseObj = [
                'data' => [
                    'title' => $data->title,
                    'description' => $data->description,
                    'is_left' => $data->is_left,
                    'alt' => $data->alt,
                    'caption' => $data->caption,
                    'thumbnail' =>$data->paragraph_image_url ?? $data->thumbnail 
                ]
            ];

            return $this->successResponse($responseObj, config('status_codes.success.ok'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->logErrorJson($e, 'Error in finding service : ');
        } catch (Exception $e) {
            return $this->logErrorJson($e, 'Error finding the services : ');
        }
    }

    public function update_paragraph(Request $request, $id)
    {
        /**
         * Update Paragraph
         * 
         * Doc: update paragraph
         *
         * @param Illuminate\Http\Request $request
         * @param Integer $id App\Models\ServicesSection
         *
         * @return \Illuminate\View\View
         */

        try {
            
            
            $data = ServicesSection::findOrFail($id);

            $data_array = array_merge($request->all(), [
                'is_left' => $request->is_left == 'left' ? 1 : 0
            ]);

            // add image if it was sent and remove the link if it was available
            if ($request->hasFile('image')) {
                $data
                    ->clearMediaCollection('images')
                    ->addMedia($request->file('image'))
                    ->toMediaCollection('images');
                
                $data_array['paragraph_image_url'] = null;
                
            }
            elseif(!$request->hasFile('image') && $request->paragraph_image_url)
            {
                $data
                    ->clearMediaCollection('images');
            }

            $data->update($data_array);

            #get response obj
            $responseObj = [
                'msg' => 'services was updated',
                'status' => config('general.success.created')
            ];

            return $this->successResponse($responseObj, config('status_codes.success.created'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logErrorAndRedirect($e, 'Error in finding service location restriction : ');
            return back();
        } catch (Exception $e) {
            return $this->logErrorJson($e, 'something wrong updating the paragraph');
        }
    }
    
    public function sort(Request $request)
    {
        /**
         * Sort the services
         * 
         * Doc: sort the service according to the order
         *
         * @param Illuminate\Http\Request $request
         *
         * @return Json
         */

        try 
        {
            $order = $request->input('order');
            foreach ($order as $index => $itemId) {
                // Update the position of the record in the database based on $itemId and $index
                Services::where('id', $itemId)->update(['position' => $index + 1]);
            }

            return response()->json(['success' => true]);
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'err' => 'something wrong with sort'
            ], 500);
        }


    }
}
