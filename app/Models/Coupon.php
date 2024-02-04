<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Coupon
 *
 * This model represents coupons within the application. Coupons have attributes
 * such as coupon_name, coupon_code, usage_limit, percentage_discount, active_from,
 * active_to, discount_type, and relationships with other models.
 *
 * Why this model exists:
 * - To store and manage data related to coupons in the application.
 * - Provides a structured way to interact with coupon-related data in the database.
 *
 * @package App\Models
 */


class Coupon extends Model
{
    use HasFactory,SoftDeletes;

    
    protected $guarded = [];
    protected $table = 'coupons';

    #constants for coupons
    const DISCOUNT_TYPE_PERCENTAGE = 'Percentage';
    const DISCOUNT_TYPE_PRICE = 'Price';

    protected $fillable = [
        'coupon_name',
        'coupon_code',
        'usage_limit',
        'percentage_discount',
        'active_from',
        'active_to',
        'discount_type'
    ];

    protected $casts = [
        'active_from' => 'date',
        'active_to' => 'date',
    ];
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class,'id','coupon_id');
    }
}
