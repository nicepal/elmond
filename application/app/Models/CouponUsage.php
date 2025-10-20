<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id', 'user_id', 'discount_amount', 'order_id'
    ];

    /**
     * Get the coupon that was used.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the user who used the coupon.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}