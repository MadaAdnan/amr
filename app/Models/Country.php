<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Country
 *
 * This model represents countries within the application. Countries have attributes
 * such as name and status. Additionally, it includes a relationship with the State model.
 *
 * Why this model exists:
 * - To store and manage data related to countries in the application.
 * - Provides a structured way to interact with country-related data in the database.
 *
 * @package App\Models
 */

class Country extends Model
{
    use HasFactory;

       
    #Constants for child seat status
    const STATUS_UNASSIGNED = 'Unassigned';
    const STATUS_ACTIVE = 'Active';
    const STATUS_DISABLED = 'Disabled';
    
    protected $fillable = [
        'name',
        'status'
    ];


    public function states()
    {
        return $this->hasMany(State::class,'country_id');
    }

}
