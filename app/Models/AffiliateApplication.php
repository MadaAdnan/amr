<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AffiliateApplication
 *
 * This model represents affiliate applications submitted by potential partners.
 * Affiliate applications contain information about the applying entity, such as name,
 * contact details, fleet size, and areas of service.
 *
 * Why this model exists:
 * - To store and manage data related to affiliate applications in the application (front end).
 *
 * @package App\Models
 */



class AffiliateApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'state',
        'city',
        'address',
        'zip_code',
        'phone',
        'email',
        'website',
        'contact_person',
        'contact_phone',
        'contact_email',
        'fleet_size',
        'area_of_service',
        'airports',
        'tax_id'
    ];
    
}
