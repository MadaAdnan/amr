<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class SliderServices
 *
 * This model represents slider services in the front-end. Slider services have attributes
 * such as title, caption, link, and alternative text. It includes the handling of media for
 * images and provides a method to retrieve the first media URL (image).
 *
 * Why this model exists:
 * - To store and manage data related to slider services in the application.
 * - Handles media functionality for images associated with slider services.
 * - Includes a method to get the first media URL for images.
 *
 * @package App\Models
 */

class SliderServices extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'title',
        'caption',
        'link',
        'alt'
    ];


    protected $append = [
        'image'
    ];

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

}
