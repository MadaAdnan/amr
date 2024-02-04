<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationSetting
 *
 * This model represents notification settings within the mobile application. Notification settings
 * have attributes such as user_id and types. Additionally, it includes a relationship with
 * the User model.
 *
 * Why this model exists:
 * - To store and manage data related to notification settings in the application.
 * - Provides a structured way to interact with notification setting-related data in the database.
 *
 * @package App\Models
 */


class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'types',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
  
}
