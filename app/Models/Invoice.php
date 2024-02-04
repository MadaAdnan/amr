<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Invoice
 *
 * This model represents invoices within the application. Invoices have attributes such as
 * serial_number, trips_ids, total, and user_id.
 *
 * Why this model exists:
 * - To store and manage data related to invoices in the application.
 * - Provides a structured way to interact with invoice-related data in the database.
 *
 * @package App\Models
 */

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'trips_ids',
        'total',
        'user_id',
    ];
}
