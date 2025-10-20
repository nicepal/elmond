// Add this to the registered method or wherever user registration is completed
protected function registered(Request $request, $user)
{
    // Check for registration bonus coupons
    $registrationCoupons = Coupon::where('is_registration_bonus', true)
                                 ->where('active', true)
                                 ->where(function($query) {
                                     $query->whereNull('expires_at')
                                           ->orWhere('expires_at', '>', now());
                                 })
                                 ->get();
    
    // Assign coupons to the user
    foreach ($registrationCoupons as $coupon) {
        // You could either create a user_coupons table to store these
        // or simply notify the user about their bonus coupon
        
        // For now, let's add it to the session if they're registering and checking out
        if ($request->session()->has('cart')) {
            $request->session()->put('coupon', [
                'code' => $coupon->code,
                'id' => $coupon->id,
                'discount_type' => $coupon->discount_type,
                'discount_amount' => $coupon->discount_amount,
                'calculated_discount' => $coupon->calculateDiscount($this->getCartTotal())
            ]);
            
            // Notify user about the applied coupon
            $notify[] = ['success', 'Welcome bonus coupon applied: ' . $coupon->code];
            return redirect()->intended($this->redirectPath())->withNotify($notify);
        }
    }
    
    return redirect()->intended($this->redirectPath());
}

// Helper method to calculate cart total
private function getCartTotal()
{
    $cart = Session::get('cart', []);
    $total = 0;
    
    foreach ($cart as $item) {
        $total += (float) $item['price'] * (int) $item['qty'];
    }
    
    return $total;
}