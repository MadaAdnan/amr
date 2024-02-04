<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewBill
 *
 * This model represents bill logs within the application. Bill logs have attributes such as
 * bill_number, user_id, total_amount, card_brand, last_four, coupon_id, type, and reservation_id.
 * Additionally, it includes relationships with the User and Reservation models.
 *
 * Why this model exists:
 * - To store and manage data related to bill logs in the application.
 * - Provides a structured way to interact with bill log-related data in the database.
 *
 * @package App\Models
 */


class NewBill extends Model
{
    use HasFactory;


    protected $fillable = [
        'bill_number',
        'user_id',
        'total_amount',
        'card_brand',
        'last_four',
        'coupon_id',
        'type',
        'reservation_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function reservations()
    {
        return $this->belongsTo(Reservation::class,'reservation_id');
    }
}
