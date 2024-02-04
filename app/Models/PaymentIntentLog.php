<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentIntentLog
 *
 * This model represents logs related to payment intents associated with reservations within the application.
 * Payment intent logs have attributes such as payment_intent_id, reservation_id, and price. Additionally, it
 * includes a relationship with the Reservation model.
 *
 * Why this model exists:
 * - To store and manage logs related to payment intents for reservations in the application.
 * - Provides a structured way to interact with payment intent log-related data in the database.
 *
 * @package App\Models
 */


class PaymentIntentLog extends Model
{
    use HasFactory;

    protected $table = "payment_intent_logs";

    protected $fillable = [
        'payment_intent_id',
        'reservation_id',
        'price'
    ];

    public function reservations()
    {
        return $this->belongsTo(Reservation::class,'reservation_id','id');
    }

}
