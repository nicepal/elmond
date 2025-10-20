<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'discount_type', 'discount_amount',
        'minimum_purchase', 'usage_limit', 'usage_limit_per_user',
        'is_first_purchase_only', 'is_registration_bonus', 'applicable_courses',
        'starts_at', 'expires_at', 'active'
    ];

    protected $casts = [
        'applicable_courses' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_first_purchase_only' => 'boolean',
        'is_registration_bonus' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Get the usages for the coupon.
     */
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if the coupon is valid for use.
     */
    public function isValid()
    {
        // Check if coupon is active
        if (!$this->active) {
            return false;
        }

        // Check if coupon has expired
        if ($this->expires_at && now()->isAfter($this->expires_at)) {
            return false;
        }

        // Check if coupon start date is in the future
        if ($this->starts_at && now()->isBefore($this->starts_at)) {
            return false;
        }

        // Check if coupon has reached its usage limit
        if ($this->usage_limit && $this->usages()->count() >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if the coupon is valid for a specific user.
     */
    public function isValidForUser($userId)
    {
        // First check general validity
        if (!$this->isValid()) {
            return false;
        }

        // Check if user has already used this coupon
        if ($this->usage_limit_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        // Check if this is a first purchase only coupon
        if ($this->is_first_purchase_only) {
            $hasOrders = Enroll::where('user_id', $userId)->where('status', 1)->exists();
            if ($hasOrders) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate the discount amount for a given subtotal.
     */
    public function calculateDiscount($subtotal, $courseIds = [])
    {
        // Check if minimum purchase requirement is met
        if ($subtotal < $this->minimum_purchase) {
            return 0;
        }
        // Check if coupon is restricted to specific courses
        if (!empty($this->applicable_courses) && !empty($courseIds) && (empty($this->applicable_courses) != false)) {
            $applicableCourses = (array) $this->applicable_courses;
            $hasApplicableCourse = false;
            
            foreach ($courseIds as $courseId) {
                if (in_array($courseId, $applicableCourses)) {
                    $hasApplicableCourse = true;
                    break;
                }
            }
            if (!$hasApplicableCourse) {
                return 0;
            }
        }
        // Calculate discount based on type
        if ($this->discount_type === 'percentage') {
            return ($subtotal * $this->discount_amount) / 100;
        } else { // fixed amount
            return min($this->discount_amount, $subtotal); // Don't exceed the subtotal
        }
    }
}