<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Airline
 *
 * This model represents airlines within the application.
 * Airlines have a name and may have associated trips and reservations.
 *
 * Why this model exists:
 * - To return to the client the airlines he can choose from.
 *
 * @package App\Models
 */


class Airline extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name',
    ];

    
     public function reservations()
     {
        return $this->hasMany(Reservation::class,'id','airline_id');
     }

     
}
