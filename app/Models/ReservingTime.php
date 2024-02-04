<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReservingTime
 *
 * This model represents reserving times within the application. Reserving times have attributes
 * such as the time period, associated charges, and specific hours. It includes relationships
 * with other models such as City and FleetCategory. Reserving time it's when the user make a 
 * reservation close to it's pick up time make it more expensive according to the rules the user
 * selected
 *
 * Why this model exists:
 * - To store and manage data related to reserving times in the application.
 * - Establishes relationships with City and FleetCategory models for contextual information.
 *
 * @package App\Models
 */


class ReservingTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'period', // if the user charge for 15/30/45/60 min
        'charge', // the price for the number of interval
        'city_id', // city id
        'from_hour', // mean if it's the user selected 1 hour 
        'to_hour', // mean if it's the user selected 6 hours
        'fleet_category_id' // the reserving time for cretin category
    ];


    public function cities()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function fleetCategories()
    {
        return $this->belongsTo(FleetCategory::class,'fleet_category_id');
    }

}
