<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Dashboard\AdminStoreRequest;
use App\Http\Requests\Dashboard\UpdateAdminRequest;
use App\Models\Role;
use App\Models\User;
use App\Traits\EmailTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\UtilitiesTrait;
use Auth , Alert , Exception;

class AdminController extends Controller
{
    use UtilitiesTrait , EmailTrait, JsonResponseTrait;

     /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    |
    | This handles the CRUD action's of the dashboard admins 
    |
    */

    public function index()
    {   
        /*
        * Index
        * 
        * Doc: Send the user to users page in the dashboard
        * 
        * @return \Illuminate\View\View
        */

        try 
        {
            #initiate eloquent query
            $data = User::orderBy('created_at', 'desc');
        
            #get the current user role name
            $role = Auth::user()->roleName;
        
            #if the user is super admin give all the roles except the customer else return the desired roles
            if ($role == 'Super-admin') 
            {
                $data = $data->whereHas('roles', function ($q) {
                    $q->where('name', '!=', 'Customer');
                })->paginate(config('general.dashboard_pagination_number'));
                
            } 
            else 
            {
                $data = $data->whereHas('roles', function ($q) {
                    $q->whereIn('name', config('general.seo_admin_permitted_roles'));
                })->paginate(config('general.dashboard_pagination_number'));
            }

            #send the user to the admins view
            return view('dashboard.admins.index', compact('data'));

        } 
        catch (\Exception $e) 
        {
            #Log the exception
            \Log::error('Error in Dashboard Index: ' . $e->getMessage());
        
            #Redirect to an error view with a specific message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));
            return back();
        }
        
    }

    public function create()
    {
        /**
         * Create
         * 
         * Doc: send the user to create admin page with allowed to assign roles
         * 
         * @return \Illuminate\View\View
         */

        try
        {
            #get the allowed roles
            $roles = Role::whereIn('name', config('general.roles_admin_can_assign'))->get();

            return view('dashboard.admins.create', compact('roles'));
        }
        catch(Exception $e)
        {
            #Log the exception
            \Log::error('Error in Dashboard create: ' . $e->getMessage());
        
            #Redirect to an error view with a specific message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));
            return back();
        }
    }

    public function edit($id)
    {
        /**
         * Edit
         * 
         * Doc: send the user to edit admin page with allowed to assign roles
         * 
         * @param id The user id
         * @return \Illuminate\View\View
         */

        try
        {
            #find the user need to be edited
            $data = User::whereHas('roles', function ($q) {
                $q->whereIn('name', config('general.dashboard_roles'));
            })->findOrFail($id);

            #get the allowed roles
            $roles = Role::whereIn('name', config('general.roles_admin_can_assign'))->get();

            return view('dashboard.admins.edit', compact('roles', 'data'));

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            #log the Exception
            \Log::error('User id was not found in the edit user dashboard: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Please check the user id was sent', 'error');
            return back();
        }
        catch(Exception $e)
        {
            // Log the exception
            \Log::error('Error in Dashboard create: ' . $e->getMessage());
        
            // Redirect to an error view with a specific message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));

            return back();
        }
    }

    public function store(AdminStoreRequest $request)
    {
        /**
         * Store
         * 
         * Doc: send the user to edit admin page with allowed to assign roles
         * 
         * @param request have the user information
         * @return \Illuminate\Http\RedirectResponse
         */

        try 
        {
            #generate a password for the user to be sent email
            $generatePassword = $this->userGeneratePassword(5);
            
            #get the data sent to the user and encrypt the password than save the data
            $data = array_merge($request->all(),['password'=>bcrypt($generatePassword)]);
            $user = User::create($data);

            #give the user the selected role
            $user->syncRoles([$request->role_id]);

            #send the generated password to the created user email
            $this->sentGeneratedPasswordEmail($user->email,$generatePassword);

            #if the user is driver send it to the driver dashboard else to the admin
            if ($user->roleName == 'Driver') 
            {
                return redirect()->route('dashboard.drivers.index');
            } 
            else 
            {
                return redirect()->route('dashboard.admins.index');
            }

        } 
        catch (Exception $e) 
        {

            // Log the exception
            \Log::error('Error in Dashboard create admin: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));
            back();
        }
    }

    public function update(UpdateAdminRequest $request, $id)
    {
        /**
         * Update
         * 
         * Doc: update the selected user information this will be used for update profile too
         * 
         * @param request have the user information
         * @param id selected user id
         * @return \Illuminate\Http\RedirectResponse
         */

        try 
        {
            #get the user
            $user = User::findOrFail($id);
            
            #get user updated data
            $data = array_merge($request->except('email'),[
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone ? $request->phone : $user->phone
            ]);

            #check if the old password is matched with the provided one if the user sent want to update the password else remove the password date from the array
            if ($request->password) 
            {
                if(Hash::check($request->oldPassword, $user->password))
                {
                    #get the new password
                    $new_password = $request->password;
                        
                    #if the new password was provided encrypt it
                    if($new_password)
                    {
                        $data = array_merge($data,[
                            'password'=>bcrypt($new_password)
                        ]);
                    }
                }
                else
                {
                    Alert::toast('Incorrect Password', 'error');
                    return back();
                }
            }
            else
            {
                #remove the password value from the request if the password was sent null
                unset($data['password'], $data['password_confirmation'],$data['oldPassword']);
            }
          
            #update user info
            $user->update($data);

            #update user role
            if($request->role_id)
            {
                $user->syncRoles([$request->role_id]); 
            }

            #send the user to the dashboard home the successfully toast
            Alert::toast('Update successfully', 'success');
            return redirect()->route('dashboard.home');

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            #log the Exception
            \Log::error('User id was not found in the update user dashboard: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Please check the user id was sent', 'error');
            return back();
        }
        catch (Exception $e) 
        {
            
            #log the Exception
            \Log::error('Updating the user information in the dashboard: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));
            return back();
        }
    }

    public function generatePassword($id)
    {
        /**
         * Generate Password
         * 
         * Doc: Generate a new password for the user and send it to him on his email
         * 
         * @param id selected user id
         * @return \Illuminate\Http\RedirectResponse
         * 
         */

        try
        {
            #find the user
            $user = User::findOrFail($id);

            #generate a new password
            $generated_new_password = $this->userGeneratePassword(5);

            #update the current user password
            $user->update([
                'password'=>bcrypt($generated_new_password)
            ]);
            
            #send an email with the generated password
            $this->sentGeneratedPasswordEmail($user->email,$generated_new_password);
           
            #redirect the user to the admin's page the success message
            Alert::toast('Password was updated', 'success');
            return redirect()->route('dashboard.admins.index');

        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            #log the Exception
            \Log::error('User id was not found in the generate password user dashboard: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Please check the user id was sent', 'error');
            return back();
        }
        catch(Exception $e)
        {
            #log the Exception
            \Log::error('Generating a new password for the user: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));
            return back();
        }

    }

    public function update_profile()
    {
        /**
         * Update Profile
         * 
         * Doc: send the user to the update profile page
         * 
         * @return \Illuminate\View\View
         * 
         */

        try
        {
            #get user info
            $data = Auth::user();

            #send the user to the view 
            return view('dashboard.admins.update_profile', compact('data'));
        }
        catch(Exception $e)
        {
            #log the Exception
            \Log::error('Opening the update profile page: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'));
            return back();
        }
       
    }

    public function change_status($id)
    {
        /**
         * Change Status
         * 
         * Doc: changing the status of the selected user
         * 
         * @param id selected user id
         * @return json
         * 
         */

        try
        {
            #get the user info
            $user = User::findOrFail($id);

            #updated status
            $updated_status = false;
            
            #switch between active and not active
            if ($user->is_deactivated) 
            {
                $updated_status = false;
            } 
            else 
            {
                $updated_status = true;
            }

            #update user info
            $user->update([
                'is_deactivated'=>$updated_status
            ]);

            #send an alert to the client informing them about the update
            Alert::toast('Status was updated', 'success');

            #return to the front end with updated status
            $responseObj = [
                'msg' => 'status was changed',
                'status' => config('status_codes.success.updated')
            ];
            return $this->successResponse($responseObj,config('status_codes.success.updated'));
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            #log the Exception
            \Log::error('User id was not found in the change status user dashboard: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Please check the user id was sent', 'error');

            #an json with the error message
            return $this->errorResponse('Please check the user id was sent',config('status_codes.client_error.unprocessable'));

        }
        catch(Exception $e)
        {
             #log the Exception
             \Log::error('Updating the user status in the admin dashboard section: ' . $e->getMessage());

             #an json with the error message
             return $this->errorResponse('Something went wrong sending the verify the reset password email, please contact us at '.config('general.support_email'),config('status_codes.server_error.internal_error'));
        }
    }

    public function delete($id)
    {
         /**
         * Delete
         * 
         * Doc: Deleting the user from the system
         * 
         * @param id selected user id
         * @return json
         * 
         */

        try {
            #find the selected user
            $data = User::whereHas('roles', function ($q) {
                $q->where('name', '!=', 'Blogger');
            })->findOrFail($id);

            #add deleted to he's email so if there was another customer want to use the email 
            $email = $data->email . '(was deleted)';
            $data->update([
                'email'=>$email
            ]);

            #soft delete the user
            $data->delete();

            #inform the user the action was done successfully. 
            Alert::toast('User was deleted', 'success');

             #return to the front end json with deleted status
             $responseObj = [
                'msg' => 'User was deleted',
                'status' => config('status_codes.success.deleted')
            ];
            return $this->successResponse($responseObj,config('status_codes.success.deleted'));

        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            #log the Exception
            \Log::error('User id was not found in the delete user dashboard: ' . $e->getMessage());

            #back with an error message
            Alert::toast('Please check the user id was sent', 'error');

            #an json with the error message
            return $this->errorResponse('Please check the user id was sent',config('status_codes.client_error.unprocessable'));

        }
        catch (Exception $e) {
            #log the Exception
            \Log::error('Updating the user status in the admin dashboard section: ' . $e->getMessage());

            #an json with the error message
            return $this->errorResponse('Error deleting the user',config('status_codes.server_error.internal_error'));
        }

    }

}