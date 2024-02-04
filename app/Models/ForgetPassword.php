<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ForgetPassword
 *
 * This model represents forget password attempts within the application. Forget password
 * attempts have attributes such as user_id, code, and a unique identifier (id). Additionally,
 * it includes configuration for table name and guarded attributes.
 *
 * Why this model exists:
 * - To store and manage data related to forget password attempts in the application.
 * - Provides a structured way to interact with forget password-related data in the database.
 *
 * @package App\Models
 */


class ForgetPassword extends Model
{
    use HasFactory;
    protected $table = 'forget_passwords';
    protected $guarded = [];
    
    protected $fillable = [
        'id',
        'user_id',
        'code'
    ];
}
