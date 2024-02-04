<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\LogOptions;


/**
 * Class Post
 *
 * This model represents posts within the application. Posts have attributes such as slug, title, content, status, date,
 * author, user_id, seo_title, seo_description, reject_note, admin_reject_note, and summary. Additionally, it includes
 * relationships with the User, Category, Tag, and Comment models, and implements the HasMedia trait for handling media.
 *
 *
 * Why this model exists:
 * - To store and manage data related to posts in the application.
 * - Provides a structured way to interact with post-related data in the database.
 *
 * @package App\Models
 */

class Post extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();

    }
    protected $guarded = [];


    protected $fillable = [
        'slug',
        'title',
        'content',
        'status',
        'date',
        'author',
        'user_id',
        'seo_title',
        'seo_description',
        'reject_note',
        'admin_reject_note',
        'summary'
    ];

    protected $casts = [
        'date'=>'datetime:Y-m-d H:00'
    ];

    protected $append = [
        'avatar'
    ];

    const PUBLISH = 'publish';
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const REJECTED = 'rejected';
    const IN_PROGRESS = 'in-progress';
    const ADMIN_REJECT = 'admin_reject';

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, "post_categories");
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, "post_tags");
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function keywords()
    {
        return $this->belongsToMany(Tag::class,'tag_keyword','post_id','tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'post_id');
    }

    
}
