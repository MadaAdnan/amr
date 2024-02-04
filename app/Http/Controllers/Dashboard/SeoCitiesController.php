<?php

namespace App\Http\Controllers\Dashboard;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\SeoCity;
use DB;
use Exception;
use Illuminate\Http\Request;

class SeoCitiesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Seo City Controller
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
         * Doc: Store a new SEO city record.
         *
         * @param  \Illuminate\Http\Request  $request
         *         - name: The name of the city.
         *         - status: The status of the city.
         *         - slug: The slug of the city.
         *         - country_id: The id of the country.
         * @return \Illuminate\Http\RedirectResponse
         *         The function returns a redirect back with a success message if the city is added successfully, success toast is displayed.
         *
         * @throws \Exception
         *         If an exception occurs during the transaction, it is rethrown, and an error toast is displayed.
         */
        try {

            $input_request = [
                'name' => $request->cityName,
                'status' => $request->status,
                'slug' => $request->slug,
                'country_id' => $request->id,
                'seo_title'=> $request->seo_title,
                'seo_description'=> $request->seo_description,
                'seo_key_phrase'=> $request->seo_key_phrase,
                'services_header'=>$request->header,
                'services_content'=>$request->content,
            ];
            DB::transaction(function () use ($input_request) {
                SeoCity::create($input_request);
            });
            Alert::toast('The city added successfully', 'success');
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
         * Doc: Edit an SEO City record.
         *
         * @param  \Illuminate\Http\Request  $request
         *         - name: The name of the city.
         *         - status: The status of the city.
         *         - slug: The slug of the city.
         *         - country_id: The id of the country.
         * @param $id 
         *         - country id
         * @return \Illuminate\Http\RedirectResponse
         *         The function returns a redirect back with a success message if the country is added successfully.
         *
         * @throws \Exception
         *         If an exception occurs during the transaction, it is rethrown, and an error toast is displayed.
         */

        try 
        {
            $city = SeoCity::find($id);

            $input_request = [
                'name' => $request->cityName,
                'status' => $request->status,
                'slug' => $request->slug,
                'country_id' => $request->countryId,
                'seo_title'=> $request->seo_title,
                'seo_description'=> $request->seo_description,
                'seo_key_phrase'=> $request->seo_key_phrase,
                'services_header'=>$request->header,
                'services_content'=>$request->content,
            ];
            DB::transaction(function () use ($input_request,$city) {
                $city->update($input_request);
            });
            Alert::toast('The city updated successfully', 'success');
            return back();


        } catch (Exception $e) {
             // Log the exception
             \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

             #back with an error message
             Alert::toast('Something went wrong sending the verify the reset password email, please contact us at ' . config('general.support_email'));
             back();

        }
    }

    public function delete($id)
    {
        /**
         * delete function
         * 
         * Doc: Delete a specific SEO city.
         *
         * @param  int  $id
         *         The ID of the SEO city to delete.
         * @return \Illuminate\Http\RedirectResponse
         *         If the city is found and deleted successfully, it returns back with a success message.
         *         If the city is not found, it displays an error toast and redirects back.
         *
         * @throws \Exception
         *         If an exception occurs during the deletion process, it is rethrown, and an error toast is displayed.
         */
        try 
        {
            $city = SeoCity::find($id);
            if ($city) {
                $city->delete();

                Alert::toast('The city deleted successfully', 'success');
                return back();

            } else {
                Alert::toast('city not found', 'error');
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
         /**
         * activeInactiveSingle function
         * 
         * Doc: Active or Inactive Single SEO cities associated with a specific country.
         *
         * @param  int  $id
         *         The ID of the SEO city to Active or Inactive.
         * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
         *         If the country is found, it returns the view with paginated SEO cities and the country information.
         *         If there is an error, it logs the exception, displays an error toast, and redirects back.
         *
         * @throws \Exception
         *         If an exception occurs during the transaction, it is rethrown, and an error toast is displayed.
         */

        try 
        {
            $city = SeoCity::find($id);
            if ($city) {
                if ($city->status == 'Active') {
                    $city->status = 'Disabled';
                } elseif ($city->status == 'Disabled') {
                    $city->status = 'Active';
                }
                $city->save();
                Alert::toast('The status has been changed successfully', 'success');
                return back();
            } else {
                Alert::toast('Server Error', 'error');

                return back();
            }


        } 
        catch (Exception $e) 
        {

              // Log the exception
              \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

              #back with an error message
              Alert::toast('Something went wrong sending the verify the reset password email, please contact us at ' . config('general.support_email'));
              back();
        }
    }
}
