<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChildSeatReservation
 *
 * This model represents the pivot table for the many-to-many relationship
 * between reservations and child seats. It includes attributes such as
 * reservation_id, child_seats_id, and amount for the number of times the user add the child seat for the reservation.
 *
 * Why this model exists:
 * - Serves as the intermediate table in the many-to-many relationship between reservations and child seats.
 * - Stores additional information, such as the amount associated with a child seat reservation.
 *
 * @package App\Models
 */


class ChildSeatReservation extends Model
{
    use HasFactory;

    protected $table = 'child_seat_reservations';

    protected $fillable = [
        'reservation_id',
        'child_seats_id',
        'amount'
    ];
}
