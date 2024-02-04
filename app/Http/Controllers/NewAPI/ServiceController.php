<?php

namespace App\Http\Controllers\NewAPI;

use App\Models\SliderServices;
use Exception;
use App\Http\Controllers\api\BaseApiController;
use App\Traits\CalculationTrait;
use App\Traits\JsonResponseTrait;
use App\Traits\NotificationTrait;

class ServiceController  extends BaseApiController
{
     /*
    |--------------------------------------------------------------------------
    | Service Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all the services in the mobile
    |
    */

    use CalculationTrait, NotificationTrait;

    public function index()
    {
         /**
        * Index
        * 
        * Doc: get the info needed to make a reservation
        *
        *
        * @return Json
        */

        try
        {         
            $services = SliderServices::get()->map(function ($item){
                return 
                [
                    'id' => $item->id,
                    'title' => $item->title,
                    'caption'=>$item->caption,
                    'image' =>$item->image?$item->image:"",
                    'alt'=>$item->alt,
                    'link'=>$item->link,
                ];
            });

            return $this->NewResponse(true, $services , null , config('status_codes.success.ok'));

        }   
        catch (Exception $e) 
        {
            return $this->logErrorJson($e,"Error in getting services: ");
        }

    }
    
}
