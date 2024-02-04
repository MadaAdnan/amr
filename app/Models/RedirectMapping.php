<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RedirectMapping
 *
 * This model represents URL redirect mappings within the application. Redirect mappings have attributes such as
 * old_url, new_url, and is_active. Additionally, it includes configuration for fillable attributes.
 *
 * Why this model exists:
 * - To store and manage data related to URL redirect mappings in the application.
 * - Provides a structured way to interact with URL redirect mapping-related data in the database.
 * - To Add a cretin url and redirect to the new direction.
 *
 * @package App\Models
 */


class RedirectMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_url',
        'new_url',
        'is_active',
    ];
}
