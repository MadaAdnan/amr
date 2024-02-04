<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OtpEmails
 *
 * This model represents OTP (One-Time Password) codes sent via email within the application.
 * OTP emails have attributes such as email, otp_code, end_time, number_of_attempts,
 * last_attempts, and status. Additionally, it includes constants for status values.
 *
 * Why this model exists:
 * - To store and manage data related to OTP codes sent via email in the application.
 * - Provides a structured way to interact with OTP email-related data in the database.
 *
 * @package App\Models
 */


class OtpEmails extends Model
{
    use HasFactory;

    #constants for child seat status
    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;

    protected $fillable = [
        'email',
        'otp_code',
        'end_time',//drop this
        'number_of_attempts',
        'last_attempts',
        'status'
    ];
}
