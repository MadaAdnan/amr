<?php
namespace App\Http\Controllers\Chauffeur;

use App\Models\User;
use App\Http\Requests\Chauffeur\Login\LoginFormRequest;
use App\Http\Controllers\api\BaseApiController;
use App\Traits\ChauffeurResponseTrait;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends BaseApiController
{

    use ChauffeurResponseTrait;


    public function login(LoginFormRequest $request, User $user)
    {
        /**
         * login
         * 
         * Doc: This will save the user info without necessary publishing it
         * 
         * @param App\Http\Requests\Chauffeur\Login\LoginFormRequest extends \Illuminate\Foundation\Http\FormRequest $request will have the credential information
         * 
         * @return Json
         */

        try {
            $requestData = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            if (Auth::attempt($requestData) && $user->hasRole('Driver')) 
            {
                $user->fcm = $request->fcm;
                $user->save();
                $token = $user->createToken($user->id . $request->device_name . date('Y-m-d H:i:s'))->accessToken;
                return $this->NewResponse(true, $this->chauffeurResponse($user, $token), null, config('status_codes.success.ok'));
            }
            else 
            {
                return $this->NewResponse(false, null, "User not authorized to login.", config('status_codes.client_error.unauthorized'));
            }
        } catch (Exception $exception) {
            return $this->NewResponse(false, null, $exception,  config('status_codes.server_error.internal_error'));
        }
    }

}
