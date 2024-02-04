<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Reservation
 *
 * This model represents reservations within the application. Reservations have various attributes
 * such as pick-up/drop-off details, dates, times, pricing information, and more. It includes
 * relationships with other models and incorporates soft deletes and activity logging.
 *
 * Why this model exists:
 * - To store and manage data related to reservations in the application.
 * - Implements soft deletes to handle records marked as deleted.
 * - Includes activity logging to track changes to reservation data.
 *
 * @package App\Models
 */


class Reservation extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected static $logName = 'reservation';
    
    protected $fillable = [
        "pick_up_location",
        "drop_off_location",
        "pick_up_date",
        "return_date",
        "pick_up_time",
        "return_time",
        "tip",
        "price",
        "duration",
        "distance",
        "phone_primary",
        "phone_secondary",
        "flight_number",
        "comment",
        "airline_id",
        "service_type",
        "price_with_tip",
        "user_id",
        "driver_id",
        "coupon_id",
        "category_id",
        "company_id",
        "created_at",
        "updated_at",
        "transfer_type",
        "status",
        "deleted_at",
        'latitude',
        'longitude',
        'fleet_id',
        'dropoff_latitude',
        'dropoff_longitude',
        'child_seats',
        'edit_price',
        'parent_reservation_id',
        'mile',
        'company_booking_number',
        'city_id',
        'pickup_sign',
        'email_for_confirmation',
        'reject_note'
    ];

    #constants
    const PENDING = "pending";
    const ACCEPTED = "accepted";
    const ASSIGNED = "assigned";
    const ON_THE_WAY = "on the way";
    const ARRIVED_AT_THE_PICKUP_LOCATION = "arrived at the pickup location";
    const PASSENGER_ON_BOARD = "passenger on board";
    const COMPLETED = "completed";
    const CANCELED = 'canceled';
    const LATE_CANCELED = 'late canceled';
    const NO_SHOW = 'no show';
    const Draft = 'draft';
    const FAILED = 'failed';


    protected $casts = [
        'pick_up_date' => 'date'
    ];

    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();

    }
    

    public function companies()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function drivers()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function childSeats()
    {
        return $this->belongsToMany(ChildSeat::class, 'child_seat_reservations', 'reservation_id', 'child_seats_id')
        ->wherePivot('amount', '>', 0)
        ->withPivot('amount');    
    }

    public function coupons()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }


    public function fleets()
    {
        return $this->belongsTo(FleetCategory::class, 'category_id');
    }

    public function bills()
    {
        return $this->hasMany(NewBill::class, 'reservation_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id');
    }
    

    public function airlines()
    {
        return $this->belongsTo(Airline::class, 'airline_id', 'id');
    }
    

    public function paymentIntentLogs()
    {
        return $this->hasMany(PaymentIntentLog::class, 'reservation_id');
    }

    public function city()
    {
        return  $this->belongsTo(City::class,'city_id','id');
    }

    public function parent()
    {
        return  $this->hasOne(self::class,'parent_reservation_id','id');
    }
    
    public function child()
    {
        return  $this->belongsTo(self::class,'parent_reservation_id','id');
    }


}