<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class City
 *
 * This model represents cities within the application. Cities have attributes such as title, status,
 * state_id, daily pricing information, split-hour mechanism details, and relationships with other models the every city it has it's own pricing rules.
 *
 * Why this model exists:
 * - To store and manage data related to cities in the application.
 * - Provides a structured way to interact with city-related data in the database.
 *
 * @package App\Models
 */


class City extends Model
{
    use HasFactory;

    #Constants for city status
    const STATUS_UNASSIGNED = 'Unassigned';
    const STATUS_ACTIVE = 'Active';
    const STATUS_DISABLED = 'Disabled';

    protected $fillable = [
        'title',
        'status',
        'state_id',
        'daily_from',
        'daily_to',
        'daily_price',
        'split_hour_mechanism',
        'split_hour_mechanism_price',
        'city_id',
        'updated_at'
    ];


    public function states()
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function pricesRules(){
        return $this->hasMany(PriceRule::class,'city_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class,'city_id');
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class,'city_id');
    }

    public function fleets_category(){
        return $this->belongsToMany(FleetCategory::class);
    }

    public function reservingTimes()
    {
        return $this->hasMany(ReservingTime::class,'city_id');
    }

    public function getCityPrice($category_id,$service_id)
    {
       return PriceRule::where([
            ['category_id',(int)$category_id],
            ['city_id',(int)$this->id],
            ['service_id',(int)$service_id],
        ])->first();
    }

    public function parent_city()
    {
        return $this->hasMany(City::class,'city_id');
    }

    public function child_city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function services_location_restriction()
    {
        return $this->hasMany(ServiceLocationRestriction::class,'city_id');
    }

    public function reservations(){
        return $this->hasMany(Reservation::class, 'city_id');
        
    }
    public function companies(){
        return $this->hasMany(Company::class, 'city_id');
        
    }
}
