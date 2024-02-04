<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Faq
 *
 * This model represents frequently asked questions (FAQs) within the application.
 * FAQs have attributes such as question, answer, sort, type, and status.
 *
 * Why this model exists:
 * - To store and manage data related to frequently asked questions in the application.
 * - Provides a structured way to interact with FAQ-related data in the database.
 *
 * @package App\Models
 */


class Faq extends Model
{
    use HasFactory;


    #types
    const GENERAL_TYPE = 'General';
    const PROFESSIONAL_CHAUFFEUR_TYPE = 'Professional Chauffeur';
    const CANCELLATION_AND_REFUND_TYPE = 'Cancellations & Refunds';


    protected $fillable = [
        'question',
        'answer',
        'sort',
        'type'
    ];
}
