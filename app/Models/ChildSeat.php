<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class ChildSeat
 *
 * This model represents child seats available for reservation.
 * Child seats have attributes such as title, description, price, and status.
 *
 * Why this model exists:
 * - To store and manage data related to child seats in the application.
 * - Provides a structured way to interact with child seat-related data in the database.
 *
 * @package App\Models
 */


class ChildSeat extends Model
{
    use HasFactory;

    #Constants for child seat status
    const STATUS_PUBLISHED = 'Published';
    const STATUS_DISABLED = 'Disabled';

    protected $fillable = [
        'id',
        'title',
        'description',
        'price',
        'status'
    ];
    
    
    public function reservations()
    {
        return $this->belongsToMany(ChildSeat::class,'child_seat_reservations' ,'child_seats_id' , 'reservation_id');
    }
  

}
