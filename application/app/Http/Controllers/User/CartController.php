<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Helpers
    private function getCart(): array
    {
        return Session::get('cart', []);
    }

    private function saveCart(array $cart): void
    {
        Session::put('cart', $cart);
    }

    private function calculateItemPrice(Course $course): float
    {
        // Use discount if present; fallback to raw price
        $price = (float) $course->price;
        $discount = (float) ($course->discount ?? 0);
        if ($discount > 0) {
            $price = $price - ($price * $discount / 100);
        }
        // Guard against negative values
        return max(0.0, round($price, 2));
    }

    // Update the getCartTotal method to account for coupons
    private function getCartTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;
        
        foreach ($cart as $item) {
            $total += (float) $item['price'] * (int) $item['qty'];
        }
        
        // Apply coupon discount if available
        $coupon = Session::get('coupon');
        if ($coupon) {
            $total = max(0, $total - $coupon['calculated_discount']);
        }
        
        return $total;
    }

    // Update the viewCart method to pass coupon information to the view
    public function viewCart(Request $request)
    {
        $pageTitle = 'Shopping Cart';
        
        $items = $this->getCart();
        $count = count($items);
        $total = $this->getCartTotal();
        
        // Get coupon information
        $coupon = Session::get('coupon');
        $discountAmount = $coupon['calculated_discount'] ?? 0;
        $subtotal = $total + $discountAmount; // Original total before discount
        
        // Get country data for checkout
        $countries = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        
        // Check if this is an AJAX request or specifically requesting JSON
        if ($request->ajax() || $request->query('json') == 1) {
            return response()->json([
                'html' => view('user.cart.index', compact('items', 'count', 'total', 'countries', 'coupon', 'discountAmount', 'subtotal', 'pageTitle'))->render(),
                'count' => $count
            ]);
        }
        
        // For regular page requests, return the view directly
        return view('user.cart.index', compact('items', 'count', 'total', 'countries', 'coupon', 'discountAmount', 'subtotal', 'pageTitle'));
    }

    // Update the checkout method to save coupon usage
    public function processCheckout(Request $request)
    {
        // Get cart items
        $cart = $this->getCart();
        if (empty($cart)) {
            $notify[] = ['error', 'Your cart is empty'];
            return back()->withNotify($notify);
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
        
        // Apply coupon if available
        $couponDiscount = 0;
        $coupon = Session::get('coupon');
        if ($coupon) {
            $couponDiscount = $coupon['calculated_discount'];
            $total = max(0, $subtotal - $couponDiscount);
        } else {
            $total = $subtotal;
        }
        
        // Add tax
        $tax = $total * 0.18; // 18% IGST
        $grandTotal = $total + $tax;
        
        // Store course IDs in session for enrollment
        $courseIds = array_keys($cart);
        session(['enrollment_courses' => $courseIds]);
        
        // Redirect to multi-course enrollment
        return redirect()->route('user.enroll.multi');
    }

    // POST /cart/guest-checkout
    public function guestCheckout(Request $request)
    {
        // Validate the form data
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            // 'username' => 'required|string|max:40|unique:users,username',
            'email' => 'required|email|max:40|unique:users,email',
            'phone' => 'required|string',
            'country' => 'required|string',
            'password' => 'required|string|min:6|same:confirmed',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            // 'zip' => 'required|string',
        ]);
    
        try {
            // Get country information
            $countries = json_decode(file_get_contents(resource_path('views/includes/country.json')));
            $country = $request->country;
//            $country = $countries->{$request->country};
            $dialCode = $country->dial_code??'';
            $countryCode = $request->country;
    
            // Check if mobile number already exists
            $fullMobile = $dialCode . $request->phone;
            $existingUser = User::where('mobile', $fullMobile)->first();
            if ($existingUser) {
                return back()->withErrors(['mobile' => 'Mobile number already exists'])->withInput();
            }
    
            // Create the user
            $user = new User();
            $user->firstname = $request->first_name;
            $user->lastname = $request->last_name;
            $user->username = $request->username??'';
            $user->email = $request->email;
            $user->country_code = $countryCode;
            $user->mobile = $fullMobile;
            $user->password = Hash::make($request->password);
            $user->address = [
                'country' => $country,
                'address' => $request->address,
                'state' => $request->state,
                'zip' => $request->zip??'',
                'city' => $request->city
            ];
            $user->status = 1; // Active
            $user->ev = 1; // Email verified
            $user->sv = 1; // SMS verified
            $user->ts = 0; // Two factor disabled
            $user->tv = 1; // Two factor verified
            $user->save();
    
            // Send welcome email
            $this->sendWelcomeEmail($user, $request->password);
    
            // Log in the user
            Auth::login($user);
    
            // Store cart courses in session for enrollment
            $cartCourses = session('cart', []);
            if (!empty($cartCourses)) {
                // Store course IDs for multi-enrollment
                $courseIds = array_keys($cartCourses);
                session(['enrollment_courses' => $courseIds]);
                
                // Clear the cart session
                session()->forget('cart');
                
                // Redirect to multi-course enrollment page
                return redirect()->route('user.enroll.multi')
                    ->with('success', 'Account created successfully! You can now proceed with the enrollment.');
            }
    
            // Fallback redirect if no courses in cart
            return redirect()->route('user.home')
                ->with('success', 'Account created successfully!');
    
        } catch (\Exception $e) {  
            \Log::error('Guest checkout error', ['exception' => $e]);
            return back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    private function sendWelcomeEmail($user, $password)
    {
        try {

            $uname = $user->username??'-';
            // Simple email template without fancy colors
            $emailTemplate = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff;">
                <!-- Header -->
                <div style="background-color: #f5f5f5; padding: 20px; text-align: center; border: 1px solid #ddd;">
                    <h1 style="color: #333; margin: 0; font-size: 24px;">Welcome to ' . gs()->site_name . '!</h1>
                    <p style="color: #666; margin: 10px 0 0 0; font-size: 14px;">Your learning journey starts here</p>
                </div>
                
                <!-- Main Content -->
                <div style="padding: 20px; background-color: #ffffff; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                    <div style="margin-bottom: 20px;">
                        <h2 style="color: #333; margin-bottom: 10px;">Hello ' . $user->fullname . '!</h2>
                        <p style="color: #666; font-size: 14px; line-height: 1.5;">Your student account has been created successfully. We are excited to have you join our learning community!</p>
                    </div>
                    
                    <!-- Login Credentials Box -->
                    <div style="background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin: 20px 0;">
                        <h3 style="color: #333; margin: 0 0 10px 0; font-size: 16px;">Your Login Credentials</h3>
                        <div style="background-color: #fff; padding: 10px; border: 1px solid #eee;">
                            <p style="color: #333; margin: 5px 0; font-size: 14px;"><strong>Username:</strong> ' . $uname . '</p>
                            <p style="color: #333; margin: 5px 0; font-size: 14px;"><strong>Email:</strong> ' . $user->email . '</p>
                            <p style="color: #333; margin: 5px 0; font-size: 14px;"><strong>Password:</strong> ' . $password . '</p>
                        </div>
                        <p style="color: #666; font-size: 12px; margin: 10px 0 0 0;">Please keep these credentials safe and consider changing your password after first login.</p>
                    </div>
                    
                    <!-- Call to Action -->
                    <div style="text-align: center; margin: 20px 0;">
                        <a href="' . route('user.login') . '" style="background-color: #333; color: white; padding: 10px 20px; text-decoration: none; border: 1px solid #333; font-size: 14px;">Start Learning Now</a>
                    </div>
                    
                    <!-- Support Section -->
                    <div style="background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin: 20px 0;">
                        <h4 style="color: #333; margin: 0 0 10px 0; font-size: 14px;">Need Help?</h4>
                        <p style="color: #666; margin: 0; font-size: 12px;">If you have any questions or need assistance, feel free to contact our support team.</p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="background-color: #f5f5f5; padding: 15px; text-align: center; border: 1px solid #ddd;">
                    <p style="color: #666; margin: 0; font-size: 12px;">Thank you for choosing ' . gs()->site_name . ' for your learning journey!</p>
                    <p style="color: #999; margin: 5px 0 0 0; font-size: 11px;">© ' . date('Y') . ' ' . gs()->site_name . '. All rights reserved.</p>
                </div>
            </div>';
    
            // Send the simple email
            notify($user, 'DEFAULT', [
                'subject' => 'Welcome to ' . gs()->site_name . ' - Your Account is Ready!',
                'message' => $emailTemplate
            ], ['email']);
            
            \Log::info('Welcome email sent successfully to: ' . $user->email);
            
        } catch (\Exception $e) {
            // Log error but don't stop the process
            \Log::error('Failed to send welcome email to ' . $user->email . ': ' . $e->getMessage());
        }
    }

    // POST /cart/add/{course_id}
    public function addToCart(Request $request, $course_id)
    {
        $course = Course::findOrFail((int) $course_id);
    
        $cart = $this->getCart();
    
        if (!isset($cart[$course->id])) {
            $cart[$course->id] = [
                'id' => $course->id,
                'title' => $course->title ?? ($course->name ?? 'Course #'.$course->id),
                'price' => $this->calculateItemPrice($course),
                'image' => $course->image,
                'qty' => 1,
            ];
        } else {
            $cart[$course->id]['qty'] = 1;
            $cart[$course->id]['image'] = $course->image;
        }
    
        $this->saveCart($cart);
    
        return response()->json([
            'status' => 'ok',
            'message' => 'Added to cart',
            'count' => count($cart),
            'total' => $this->getCartTotal(),
            'item' => $cart[$course->id],
            'session_id' => Session::getId(),
        ]);
    }

    // Add this method to handle cart item removal
    public function removeFromCart(Request $request, $course_id)
    {
        try {
            // Get current cart
            $cart = $this->getCart();
            
            // Check if course exists in cart
            if (isset($cart[$course_id])) {
                // Remove the course from cart
                unset($cart[$course_id]);
                
                // Save updated cart
                $this->saveCart($cart);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Course removed from cart successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Course not found in cart'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing item from cart'
            ], 500);
        }
    }

    // Add this method to get cart count for AJAX requests
    public function getCount()
    {
        $cart = $this->getCart();
        return response()->json([
            'count' => count($cart)
        ]);
    }
}