<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class ChauffeurApplication
 *
 * This model represents chauffeur applications submitted by potential partners.
 * Chauffeur applications contain information about the applying entity, such as name,
 * contact details, fleet size, and areas of service.
 *
 * Why this model exists:
 * - To store and manage data related to chauffeur applications in the application (front end).
 *
 * @package App\Models
 */


class ChauffeurApplication extends Model implements HasMedia
{
    use HasFactory ,InteractsWithMedia;

    protected $guarded = [];

    protected $append = [
        'avatar'
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'state',
        'city',
        'address',
        'date_of_birth',
        'experience_years',
        'availability',
        'texas_license',
        'houston_limo_license',
        'houston_limo_license',
        'additional_information',
    ];

    public function getAvatarAttribute()
    {
        return $this->getFirstMediaUrl('files');
    }
}