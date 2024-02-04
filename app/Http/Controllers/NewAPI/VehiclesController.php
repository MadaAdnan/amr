<?php

namespace App\Http\Controllers\NewAPI;

use App\Models\FleetCategory;
use App\Traits\LogErrorAndRedirectTrait;
use Exception;
use App\Http\Controllers\Controller;

class VehiclesController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Fleet Category Controller
    |--------------------------------------------------------------------------
    |
    | responsible for all fleet category controller in the mobile
    |
    */

    use LogErrorAndRedirectTrait;

    public function index()
    {
          /**
        * Index
        * 
        * Doc: get the fleet category
        *
        *
        * @return Json
        */

        try
        {
            $data = FleetCategory::all()
            ->map(function ($item) {
                $data = [
                    "id" => $item->id,
                    "vehicle_type" => $item->title,
                    "description" => $item->fleets->pluck('title')->implode(', '),
                    "vehicle_name" => $item->title, // Get titles from all related fleets
                    "vehicle_image" => $item->avatar, // Get titles from all related fleets
                    "number_of_passengers" => $item->passengers,
                    "number_of_suitcases" => $item->luggage	,
                    "created_at" => $item->created_at,
                    "updated_at" => $item->updated_at,
                ];
                return $data;
            });
    
            return $this->NewResponse(true, $data, null, config('status_codes.success.ok'));
        }
        catch(Exception $e)
        {
            return $this->logErrorJson($e,"Log error for fleet category: ");
        }
    }
}
