<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    /**
     * Apply a coupon to the cart.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        $couponCode = $request->coupon_code;
        $coupon = Coupon::where('code', $couponCode)->where('active', true)->first();
        // Check if coupon exists
        if (!$coupon) {
            $notify[] = ['error', 'Invalid coupon code'];
            return back()->withNotify($notify);
        }

        // Check if coupon is valid
        if (!$coupon->isValid()) {
            $notify[] = ['error', 'This coupon is no longer valid'];
            return back()->withNotify($notify);
        }

        // Check if user is logged in and coupon is valid for this user
        if (auth()->check() && !$coupon->isValidForUser(auth()->id())) {
            $notify[] = ['error', 'You are not eligible to use this coupon'];
            return back()->withNotify($notify);
        }

        // Get cart items
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            $notify[] = ['error', 'Your cart is empty'];
            return back()->withNotify($notify);
        }

        // Calculate cart subtotal
        $subtotal = 0;
        $courseIds = [];
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
            $courseIds[] = $item['id'];
        }

        // Check minimum purchase requirement
        if ($subtotal < $coupon->minimum_purchase) {
            $notify[] = ['error', 'Minimum purchase amount of ₹' . number_format($coupon->minimum_purchase, 2) . ' required'];
            return back()->withNotify($notify);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($subtotal, $courseIds);
        // dd($subtotal,$discount,$courseIds);
        if ($discount <= 0) {
            $notify[] = ['error', 'This coupon cannot be applied to your cart items'];
            return back()->withNotify($notify);
        }

        // Store coupon in session
        Session::put('coupon', [
            'code' => $coupon->code,
            'id' => $coupon->id,
            'discount_type' => $coupon->discount_type,
            'discount_amount' => $coupon->discount_amount,
            'calculated_discount' => $discount
        ]);

        $notify[] = ['success', 'Coupon applied successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Remove a coupon from the cart.
     */
    public function removeCoupon()
    {
        Session::forget('coupon');
        
        $notify[] = ['success', 'Coupon removed successfully'];
        return back()->withNotify($notify);
    }
}