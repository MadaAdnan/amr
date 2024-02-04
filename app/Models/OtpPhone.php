<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OtpPhone
 *
 * This model represents OTP (One-Time Password) codes sent via phone number within the application.
 * OTP phones have attributes such as device_id, phone, otp_code, end_time, number_of_attempts,
 * last_attempts, and status. Additionally, it includes constants for status values and a relationship
 * with the OtpDevice model.
 *
 * Why this model exists:
 * - To store and manage data related to OTP codes sent via phone in the application.
 * - Provides a structured way to interact with OTP phone-related data in the database.
 *
 * @package App\Models
 */


class OtpPhone extends Model
{
    use HasFactory;

    #constants for child seat status
    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;

    protected $fillable = [
        'device_id',
        'phone',
        'otp_code',
        'end_time',
        'number_of_attempts',
        'last_attempts',
        'status'
    ];

    public function device()
    {
        return $this->belongsTo(OtpDevice::class,'device_id');
    }
}
