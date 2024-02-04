<?php

namespace App\Traits;

trait ChauffeurResponseTrait {

    public function chauffeurResponse($user,$token)
    {
        return [
            "user_id" => $user->id,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "phone" => $user->phone,
            "country_code" => (int) $user->country_code,
            "image" => $user->image,
            "deactivated" => !$user->is_deactivated ? 0 : $user->is_deactivated,
            "token" => $token,
        ];

    }

}
