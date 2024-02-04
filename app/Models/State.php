<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class State
 *
 * This model represents states in the application. States have attributes such as name,
 * country_id, and status. It includes relationships with countries, cities, and companies.
 *
 * Why this model exists:
 * - To store and manage data related to states in the application.
 * - Provides relationships with countries, cities, and companies.
 *
 * @package App\Models
 */


class State extends Model
{
    use HasFactory;

    #constants
    const STATUS_ACTIVE = 'Active';
    const STATUS_DISABLED = 'Disabled';
    const STATUS_UNASSIGNED = 'Unassigned';

    protected $fillable = [
        'name',
        'country_id',
        'status'
    ];


    public function countries()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class,'state_id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class,'company_id');
    }
}
