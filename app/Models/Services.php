<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Services
 *
 * This model represents services offered in the application. Services have attributes such as
 * title, description, status, and SEO-related information this will appear on the front end. It includes a relationship with the
 * ServicesSection model for organizing service sections.
 *
 * Why this model exists:
 * - To store and manage data related to services offered in the application.
 * - Establishes a relationship with the ServicesSection model for organizing service sections.
 *
 * @package App\Models
 */


class Services extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();

    }
    protected $fillable = [
        'title',
        'short_title',
        'description',
        'icons_section',
        'status',
        'slug',
        'is_orphan',
        'seo_title',
        'seo_description',
        'seo_key_phrase',
        'position',
        'city_id',
        'image_url',

    ];

    #constants
     const STATUS_DRAFT = 'Draft';
     const STATUS_PUBLISHED = 'Published';

    protected $append = [
        'image',
    ];

    protected $casts = [
        'created_at'=>'datetime:Y-m-d H:00'
    ];
    
    public function sections()
    {
        return $this->hasMany(ServicesSection::class,'service_id');
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function seoCity(){
        return $this->belongsTo(SeoCity::class,'city_id');
    }
}
