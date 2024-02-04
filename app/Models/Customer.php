<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     /**
     * Current 
     * Search for an api to generate a stripe customer id without making any bill
     */

    use HasFactory;

    protected $table = 'customers';
    protected $guarded = [];
    
    protected $fillable = [
        'first_name',   
        'last_name',   
        'email',   
        'phone',   
        'comment',   
        'airline',   
        'flight_number',   
        'user_id',   
    ];
}