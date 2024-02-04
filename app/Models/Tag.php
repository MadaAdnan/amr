<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * This model represents tags in the application. Tags have attributes such as title,
 * slug, subject, strength, monthly_volume, and is_keyword. It includes relationships
 * with posts, post keywords, and page keywords.This tags used for posts (blog)
 *
 * Why this model exists:
 * - To store and manage data related to tags in the application.
 * - Provides relationships with posts, post keywords, and page keywords.
 *
 * @package App\Models
 */


class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $fillable = [
        'title',
        'slug',
        'subject',
        'strength',
        'monthly_volume',
        'is_keyword'
    ];

     #constants
     const IS_KEYWORD = 1;
     const IS_TAGS = 0;

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }
    
    public function post_keywords()
    {
        return $this->belongsToMany(Post::class, 'tag_keyword','tag_id','post_id');
    }

    public function pages_keywords()
    {
        return $this->belongsToMany(Page::class,'page_keyword','tag_id','page_id');
    }
    
}

