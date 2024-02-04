<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class ContactForm
 *
 * This model represents entries from the contact form within the application.
 * Entries include attributes such as fname, lname, email, phone, and message.
 *
 * Why this model exists:
 * - To store and manage data related to submissions from the contact form.
 *
 * @package App\Models
 */


class ContactForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
    ];
}
