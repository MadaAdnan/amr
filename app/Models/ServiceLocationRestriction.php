<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceLocationRestriction
 *
 * This model represents service location restrictions within the application. Service location
 * restrictions have attributes such as address, latitude, longitude, status, service type,
 * and city information. It includes a relationship with the City model for contextual information.
 *
 * Why this model exists:
 * - To store and manage data related to service location restrictions in the application.
 * - Establishes a relationship with the City model for contextual information.
 * - Can restrict the type of the 
 *
 * @package App\Models
 */


class ServiceLocationRestriction extends Model
{
    use HasFactory;

    #constants status
    const ACTIVE_STATUS = "active";
    const INACTIVE_STATUS = "inactive";

     #constants services
     const SERVICE_POINT_TO_POINT = "point_to_point";
     const SERVICE_HOURLY = "hourly";
     const SERVICE_BOTH = "both";

      #constants services limitation
      const SERVICE_LIMITATION_PICKUP = "pick_up";
      const SERVICE_LIMITATION_DROP_OFF = "drop_off";




    protected $fillable = [
        'address',
        'latitude',
        'longitude',
        'status',
        'service',
        'service_limitation',
        'city_id',
        'radius'
    ];
    
    public function cities()
    {
        return $this->belongsTo(City::class,'city_id');
    }

}
