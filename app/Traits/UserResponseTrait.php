<?php

namespace App\Traits;
use Carbon\Carbon;
use Exception;
use Stripe\StripeClient;
use App\Models\User;

trait UserResponseTrait 
{


    private function userResponse($user,$token = null)
    {
        /**
        * Response
        * 
        * Doc: user response
        *
        * @param App\Models\User $user
        * @param String $token
        *
        * @return Object
        *
        */
        $obj = [
            "user_id" => $user->id,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "phone" => $user->phone,
            "country_code" => (int)$user->country_code,
            "image" => $user->image,
            "role_id" => $user->role_id,
            "deactivated" =>!$user->is_deactivated?0:$user->is_deactivated,
            'is_verified'=>$this->user_check_customer_is_verified($user),
            "customer_id" => $this->user_add_customer_id($user),
            'is_phone_verify'=>$user->phone_verified_at ? true : false
        ];

        if($token)
        {
            $obj['token'] = $token;
        }

        return $obj;
        
    }

    private function user_check_customer_is_verified($user)
    {
         /**
        * Check customer is verified
        * 
        * Doc: check if customer is verified if it's before 5/9/2023 mean it's an old customer so verified
        *
        * @param App\Models\User $user
        *
        * @return Boolean
        *
        */

        try
        {
            /** DATE OF CREATION FOR VERIFIED EMAIL*/
            $validation_date = Carbon::create(2023, 9, 5);
            $creation_date = Carbon::create($user->created_at);
            $isBefore = $creation_date->isBefore($validation_date);

            if($isBefore&&!$user->email_verified_at) 
            {
                $user->update([
                    'email_verified_at'=>Carbon::now()
                ]);
                return true;
            }
            if(!$user->email_verified_at)
            {
                return false;
            }

            return true;
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e, 'check customer is verified error : ');
        }
    }

    public function user_add_customer_id($user)
    {
         /**
        * Add customer id
        * 
        * Doc: create a customer id for the users who don't have it
        *
        * @param App\Models\User $user
        *
        * @return String
        *
        */

        try
        {
            $user_stripe_id = $user->stripe_id;
            $stripe_id = $user_stripe_id;
            
            if(!$stripe_id)
            {
                $stripe = new StripeClient(env('STRIPE_SECRET'));
                
                $customer = $stripe->customers->create([
                    'email' => $user->email, 
                    'name' => $user->full_name,
                ]);
             
                $stripe_id = $customer->id;
    
                $user = User::find($user->id);
                $user->stripe_id = $stripe_id;
                $user->save();
            }
    
            return $stripe_id;
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,'Error adding customer id');
        }
    }


}