<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


/**
 * Class Fleet
 *
 * This model represents a fleet of vehicles within the application. Fleets have
 * attributes such as title, slug, seats, luggage, passengers, image_alt, category_id,
 * content, seo_title, license, user_id, seo_description, seo_keyphrase, and status.
 * Additionally, it includes relationships with other models.
 *
 * Why this model exists:
 * - To store and manage data related to fleets of vehicles in the application.
 * - Provides a structured way to interact with fleet-related data in the database.
 * - Fleet is the vehicles inside the fleet category
 *
 * @package App\Models
 */

class Fleet extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,HasFactory;

    protected $guarded = [];
    
    #constants for child seat status
    const PUBLISH = "publish";
    const DRAFT = "draft";

    protected $append = [
        'avatar'
    ];

    protected $fillable = [
        'title',
        'slug',
        'seats',
        'luggage',
        'passengers',
        'image_alt',
        'category_id',
        'content',
        'seo_title',
        'license',
        'user_id',
        'seo_description',
        'seo_keyphrase'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function category()
    {
        return $this->belongsTo(FleetCategory::class, "category_id");
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class,'fleet_id');
    }

}
