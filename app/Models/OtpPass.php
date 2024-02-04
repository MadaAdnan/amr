<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OtpPass
 *
 * This model represents OTP (One-Time Password) passes for phone-based verification within the application.
 * OTP passes are associated with users and can be used for authentication or verification purposes.
 *
 * Why this model exists:
 * - To store and manage data related to OTP passes for phone-based verification in the application.
 * - Provides a structured way to interact with OTP pass-related data in the database.
 *
 * @package App\Models
 */


class OtpPass extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'phone',
        'otp_code',
        'is_used'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
