<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * This model represents comments within the application for the posts (Blog). Comments have attributes
 * such as text, user_id, comment_id, post_id, email, name, and status.
 *
 * Why this model exists:
 * - To store and manage data related to comments on posts in the application.
 * - Provides a structured way to interact with comment-related data in the database.
 *
 * @package App\Models
 */



class Comment extends Model
{
    use HasFactory;

    #Constants for comment status
    const STATUS_PUBLISH = "Publish";
    const STATUS_PENDING = "Pending";
    const STATUS_REHECTED = "Rejected";
    

    protected $fillable = [
        'text',
        'user_id',
        'comment_id',
        'post_id',
        'email',
        'name',
        'status'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class,'comment_id');
    }

    public function replies()
    {
        return $this->belongsTo(Comment::class,'comment_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function posts()
    {
        return $this->belongsTo(Post::class,'post_id');
    }



}
