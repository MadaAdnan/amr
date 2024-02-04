<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 *
 * This model represents events within the application. Events have attributes
 * such as name, start_date, end_date, start_time, end_time, status, city_id,
 * latitude, longitude, radius, radius_area, price, address, discount_type,
 * apply_for, service_type, endless, and additional pricing-related attributes.
 * Additionally, it includes a relationship with the City model.
 *
 * Why this model exists:
 * - To store and manage data related to events in the application.
 * - Provides a structured way to interact with event-related data in the database.
 * - Event will add a pricing to the reservation if it's inside the radius
 *
 * @package App\Models
 */


class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'city_id',
        'latitude',
        'longitude',
        'radius',
        'radius_area',
        'price',
        'address',
        'status',
        'discount_type',
        'apply_for',
        'service_type',
        'endless'
    ];
    
    
    #constants for child seat status
    const STATUS_ACTIVE = 'Active';
    const STATUS_DISABLED = 'Disabled';

    #apply for constants
    const APPLY_FOR_BOTH = 'Both';
    const APPLY_FOR_PICKUP = 'Pickup';
    const APPLY_FOR_DROP_OFF = 'DropOff';

    #discount type constants
    const DISCOUNT_TYPE_PRICE = 'Price';
    const DISCOUNT_TYPE_PERCENTAGE = 'Percentage';


    protected $casts = [
        'start_date'=>'date',
        'end_date'=>'date',
        'start_time'=>'datetime:H:i',
        'end_time'=>'datetime:H:i'
    ];

    public function cities()
    {
        return $this->belongsTo(City::class,'city_id');
    }
}
