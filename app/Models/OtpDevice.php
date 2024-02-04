<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OtpDevice
 *
 * This model represents OTP (One-Time Password) devices within the application. OTP devices
 * have attributes such as device, number_of_attempts, and blocked_at. Additionally, it includes
 * a relationship with the OtpPhone model to track attempts for the devices.
 *
 * Why this model exists:
 * - To store and manage data related to OTP devices in the application.
 * - Provides a structured way to interact with OTP device-related data in the database.
 *
 * @package App\Models
 */

class OtpDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device',
        'number_of_attempts',
        'blocked_at',
    ];

    public function phones()
    {
        return $this->hasOne(OtpPhone::class,'device_id', 'id');
    }
}
