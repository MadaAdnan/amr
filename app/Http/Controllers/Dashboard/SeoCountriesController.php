<?php

namespace App\Http\Controllers\Dashboard;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\SeoCity;
use App\Models\SeoCountry;
use DB;
use Exception;
use Illuminate\Http\Request;

class SeoCountriesController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Seo Countries Controller
    |--------------------------------------------------------------------------
    |
    | go to the seo City page
    |
    */

    public function store(Request $request)
    {
        /**
         * Store function
         * 
         * Doc: Store a new SEO country record.
         *
         * @param  \Illuminate\Http\Request  $request
         *         - country_name: The name of the country.
         *         - status: The status of the country.
         *         - title: The title of the cities Page that belongs to the current country.
         *         - content: The content of the cities Page that belongs to the current country
         * @return \Illuminate\Http\RedirectResponse
         *         The function returns a redirect back with a success message if the country is added successfully.
         *
         * @throws \Exception
         *         If an exception occurs during the transaction, it is rethrown, and an error toast is displayed.
         */
        try {
            
            $input_request = [
                'name' => $request->country_name,
                'status' => $request->status,
                'title' => $request->title,
                'content' => $request->content,
                'seo_city_title'=>$request->seoTitleValue,
                'seo_city_key_phrase'=>$request->seoKeyPhraseValue,
                'seo_city_description'=>$request->seoDescriptionValue,
            ];
            DB::transaction(function () use ($input_request) {
                SeoCountry::create($input_request);
            });
            Alert::toast('The country added successfully', 'success');
            return back();


        } catch (Exception $e) {

            // Log the exception
            \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at ' . config('general.support_email'));
            back();
        }
    }

    public function update(Request $request,$id)
    {
        /**
         * Edit function
         * 
         * Doc: Edit an SEO country record.
         *
         * @param  \Illuminate\Http\Request  $request
         *         - country_name: The name of the country.
         *         - status: The status of the country.
         *         - title: The title of the cities Page that belongs to the current country.
         *         - content: The content of the cities Page that belongs to the current country
         * @param $id 
         *         - country id
         * @return \Illuminate\Http\RedirectResponse
         *         The function returns a redirect back with a success message if the country is added successfully.
         *
         * @throws \Exception
         *         If an exception occurs during the transaction, it is rethrown, and an error toast is displayed.
         */
        try {
          
           
            $country = SeoCountry::find($id);
          
            $update_request = [
                'name' => $request->country_name,
                'status' => $request->status,
                'title' => $request->title,
                'content' => $request->content,
                'seo_city_title'=>$request->seoTitleValue,
                'seo_city_key_phrase'=>$request->seoKeyPhraseValue,
                'seo_city_description'=>$request->seoDescriptionValue,
            ];
            DB::transaction(function () use ($update_request,$country) {
                $country->update($update_request);
            });
            Alert::toast('The country added successfully', 'success');
            return back();


        } catch (Exception $e) {

            // Log the exception
            \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at ' . config('general.support_email'));
            back();
        }
    }



    public function show($id)
    {

        /**
         * show function
         * 
         * Doc: Display SEO cities associated with a specific country.
         *
         * @param  int  $id
         *         The ID of the SEO country to display.
         * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
         *         If the country is found, it returns the view with paginated SEO cities and the country information.
         *         If there is an error, it logs the exception, displays an error toast, and redirects back.
         *
         * @throws \Exception
         *         If an exception occurs during the transaction, it is rethrown, and an error toast is displayed.
         */
        try {
            $country = SeoCountry::find($id);
            

            if ($country) {
                $cities = SeoCity::where('country_id', $id)->paginate(10);
                return view('dashboard.seo_cities.index', compact('cities', 'country'));
            }

        } catch (Exception $e) {

            // Log the exception
            \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at ' . config('general.support_email'));
            back();
        }
    }
    public function delete($id)
    { /**
      * delete function
      * 
      * Doc: Delete a specific SEO country.
      *
      * @param  int  $id
      *         The ID of the SEO country to delete.
      * @return \Illuminate\Http\RedirectResponse
      *         If the country is found and deleted successfully, it returns back with a success message.
      *         If the country is not found, it displays an error toast and redirects back.
      *
      * @throws \Exception
      *         If an exception occurs during the deletion process, it is rethrown, and an error toast is displayed.
      */
        try {
            $country = SeoCountry::find($id);
            if ($country) {
                $country->delete();

                Alert::toast('The country deleted successfully', 'success');
                return back();

            } else {
                Alert::toast('Country not found', 'error');
                return back();
            }
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at ' . config('general.support_email'));
            back();

        }
    }

    public function activeInactiveSingle($id)
    {
        try {
            $country = SeoCountry::find($id);
            if ($country) {
                if ($country->status == 'Active') {
                    $country->status = 'Disabled';
                } elseif ($country->status == 'Disabled') {
                    $country->status = 'Active';
                }
                $country->save();
                Alert::toast('The status has been changed successfully', 'success');
                return back();
            } else {
                Alert::toast('Server Error', 'error');

                return back();
            }


        } catch (Exception $e) {

            // Log the exception
            \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at ' . config('general.support_email'));
            back();
        }
    }


}
