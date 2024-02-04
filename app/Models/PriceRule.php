<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PriceRule
 *
 * This model represents pricing rules within the application. Pricing rules have attributes such as service_id,
 * initial_fee, minimum_mile_hour, price_per_hour, category_id, city_id, minimum_hour, extra_price_per_mile_hourly,
 * minimum_mile, and extra_price_per_mile. Additionally, it includes relationships with the ServiceType, City, and
 * FleetCategory models, and constants representing service types.
 *
 * Constants:
 * - SERVICE_TYPE_POINT_TO_POINT: Represents the point-to-point service type.
 * - SERVICE_TYPE_HOURLY: Represents the hourly service type.
 *
 * Why this model exists:
 * - To store and manage data related to pricing rules in the application.
 * - Provides a structured way to interact with pricing rule-related data in the database.
 * - initial_fee is the fee for the point to point when the user start the reservation.
 * - minimum_mile_hour The minimum mile hour inside every hour ex. if we have an reservation hourly and the number of hours is 2 and the minimum mile per hour
 *  is 10 mile so the trip mile wil be 20 mile anything above this will be considered extra.
 * - price_per_hour price for every hour the user added
 * - category_id is the fleet category id 
 * - city_id is for what city this pricing for
 * - minimum_hour the minimum hours for this fleet
 * - extra_price_per_mile_hourly the price per extra mile
 * - minimum_mile for the point to point
 * - extra_price_per_mile extra price per mile
 *
 * @package App\Models
 */


class PriceRule extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'price_rules';
    
    #constants
    const SERVICE_TYPE_POINT_TO_POINT = 1;
    const SERVICE_TYPE_HOURLY = 2;

    
    protected $fillable = [
        'service_id',
        'initial_fee',
        'minimum_mile_hour', // Max Amount Miles Per Hour
        'price_per_hour',
        'category_id',
        'city_id',
        'minimum_hour',
        'extra_price_per_mile_hourly',
        'minimum_mile', // The Minimum mil the category have for the point to point services
        'extra_price_per_mile'
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function cities()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function fleetCategories()
    {
        return $this->belongsTo(FleetCategory::class,'category_id'); 
    }



}
