<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 *
 * This model represents companies within the application . Companies have attributes
 * such as company_name, email, phone, street, country_id, state_id, city_id, and postal_code.
 * Additionally, it includes relationships with other models. Companies it's the partners of the lavish ride
 *
 * Why this model exists:
 * - To store and manage data related to companies in the application.
 * - Provides a structured way to interact with company-related data in the database.
 *
 * @package App\Models
 */

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    protected $table = 'companies';

    protected $fillable = [
        'company_name',
        'email',
        'phone',
        'street',
        'country_id',
        'state_id',
        'city_id',
        'postal_code'
    ];
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class,'company_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
}
