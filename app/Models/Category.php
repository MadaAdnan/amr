<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


/**
 * Class Category
 *
 * This model represents categories within the application.
 * Categories are used to classify posts (Blogs) and can have associated media (images, etc.).
 *
 * Why this model exists:
 * - To store and manage data related to categories in the application.
 *
 * @package App\Models
 */


class Category extends Model implements HasMedia
{
    
    #Constants for category status
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    use HasFactory,InteractsWithMedia;

    protected $guarded = [];

    protected $fillable = [
        'title',
        'slug',
        'seo_title',
        'seo_description',
        'seo_keyphrase'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_categories');
    }
}
