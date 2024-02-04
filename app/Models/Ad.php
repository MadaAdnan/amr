<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Ad
 *
 * This model represents advertisements within the application.
 * Ads can have titles, descriptions, start dates, end dates, and associated images.
 *
 * Why this model exists:
 * - To encapsulate the business logic and database interactions related to advertisements.
 * - Implements the HasMedia interface for easy integration with media (images in this case).
 *
 * @package App\Models
 */


class Ad extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    protected $append = [
        'image'
    ];

    protected $casts = [
        'start_date'=>'datetime:H:i',
        'end_date'=>'datetime:H:i',
    ];

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }
}
