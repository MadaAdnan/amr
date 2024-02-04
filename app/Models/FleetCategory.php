<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class FleetCategory
 *
 * This model represents categories of fleets within the application. Fleet categories
 * have attributes such as title, short_title, slug, image_alt, category_description,
 * passengers, flight_tracking, luggage, seo_title, seo_description, seo_keyphrase,
 * split_hour_mechanism, split_hour_mechanism_price, and pricing_rules. Additionally,
 * it includes relationships with other models. 
 *
 * Why this model exists:
 * - To store and manage data related to categories of fleets in the application.
 * - Provides a structured way to interact with fleet category-related data in the database.
 * - Every fleet category has it's own pricing rules
 * - Every fleet category has it's own city pricing
 *
 * @package App\Models
 */

class FleetCategory extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    #constants for child seat status
     const STATUS_ACTIVE = 1;
     const STATUS_INACTIVE = 0;

    protected $guarded = [];

    protected $fillable = [
        'title',
        'short_title',
        'slug',
        'image_alt',
        'category_description',
        'passengers',
        'flight_tracking',
        'luggage',
        'seo_title',
        'seo_description',
        'seo_keyphrase',
        'split_hour_mechanism',
        'split_hour_mechanism_price',
        'pricing_rules',
        'daily_from',
        'daily_to',
        'daily_price',
        'content',
    ];


    protected $append = [
        'avatar'
    ];

    public function fleets()
    {
        return $this->hasMany(Fleet::class, "category_id", "id");
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function pricingRules()
    {
        return $this->hasMany(PriceRule::class,'category_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class,'category_id');
    }

    public function reservingTimes()
    {
        return $this->hasMany(ReservingTime::class,'fleet_category_id');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class,'city_fleet_category','fleet_category_id','city_id');
    }
    
}
