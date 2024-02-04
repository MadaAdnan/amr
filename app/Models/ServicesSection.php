<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class ServicesSection
 *
 * This model represents sections associated with services in the application. Sections have
 * attributes such as title, description, and related service information. It includes
 * relationships with the Services model for the parent service and handles media for images.
 *
 * Why this model exists:
 * - To store and manage data related to sections associated with services.
 * - Establishes a relationship with the Services model for the parent service.
 * - Includes methods for retrieving the section's thumbnail and image name.
 * - This is related to the services
 *
 * @package App\Models
 */


class ServicesSection extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'is_left',
        'service_id',
        'alt',
        'caption',
        'sort_number',
        'paragraph_image_url',
    ];

    protected $append = [
        'thumbnail',
        'imageName'
    ];


    public function services()
    {
        return $this->belongsTo(Services::class, 'service');
    }


    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function getImageNameAttribute()
    {
        $mediaItems = $this->getMedia('images');
        return $mediaItems[0]->name;
    }

}
