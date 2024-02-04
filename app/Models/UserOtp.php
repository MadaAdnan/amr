<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserOtp
 *
 * This model represents OTP (One-Time Password) information for users in the application.
 * UserOtp has attributes such as user_id, otp, number_of_attempts, and last_attempts.
 * It is used to manage and store OTP-related data for user authentication.
 *
 * Why this model exists:
 * - To store and manage OTP information for users.
 * - Provides a structured way to interact with OTP-related data in the database.
 *
 * @package App\Models
 */


class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'number_of_attempts',
        'last_attempts'
    ];
}
