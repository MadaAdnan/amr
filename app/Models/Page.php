<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Page
 *
 * This model represents pages for display on the front-end of the application.
 * Pages have attributes such as title, content, slug, seo_title, seo_description,
 * seo_keywords, type, status, nav_page_id, reject_note, and a relationship with tags.
 * Additionally, it includes constants for status values and the HasMedia trait for handling media.
 *
 * Why this model exists:
 * - To store and manage data related to pages in the application.
 * - Provides a structured way to interact with page-related data in the database.
 *
 * @package App\Models
 */


class Page extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;


    #status
    const STATUS_DRAFT = 'Draft';
    const STATUS_UNDER_REVIEW = 'UnderReview';
    const STATUS_PUBLISHED = 'Published';
    const STATUS_PENDING = 'Pending';
    const STATUS_REJECTED = 'Rejected';
     

    protected $fillable = [
        'title',
        'content',
        'slug',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'type',
        'status',
        'nav_page_id',
        'reject_note'
    ];

    protected $casts = [
        'date'=>'date'
    ];

    protected $append = [
        'avatar'
    ];

    public function getAvatarAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function keywords()
    {
        return $this->belongsToMany(Tag::class,'page_keyword','page_id','tag_id');
    }

    public function navSections()
    {
        return $this->belongsTo(NavPages::class,'nav_page_id');
    }
    
}
