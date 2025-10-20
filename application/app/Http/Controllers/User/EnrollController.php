<?php

namespace App\Http\Controllers\User;

use App\Models\Course;
use App\Models\Enroll;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    public function enrollCourses(Request $request)
    {
        $pageTitle = "Enroll Courses";
        $enrolls = Enroll::with('course', 'course.category','course.quizzes')->where('user_id', auth()->id())->where('status', 1)->orderBy('id', 'desc');
        if ($request->search) {
            $enrolls = $enrolls->where('name', 'like', "%$request->search%")->paginate(getPaginate());
        } else {
            $enrolls = $enrolls->paginate(getPaginate());
        }
        return view($this->activeTemplate . 'user.enrolls.courses', compact('pageTitle', 'enrolls'));
    }

    public function allCourses(Request $request)
    {
        $pageTitle = "All Courses";
        $courses = Course::with('enrolls','category')->whereIn('status', [1])->orderBy('id', 'desc');
        if ($request->search) {
            $courses = $courses->where('name', 'like', "%$request->search%")->paginate(getPaginate());
        } else {
            $courses = $courses->paginate(getPaginate());
        }
        return view($this->activeTemplate . 'user.enrolls.all_courses', compact('pageTitle', 'courses'));
    }


    public function enroll($id)
    {
        $course = Course::where('id', $id)->where('status', 1)->first();
        
        if (!$course) {
            $notify[] = ['error', 'Your course is not valid'];
            return back()->withNotify($notify);
        }

        // Check if user is not authenticated
        if (!Auth::check()) {
            // Add course to cart
            $cart = Session::get('cart', []);
            
            // Calculate price with discount if applicable
            $price = (float) $course->price;
            $discount = (float) ($course->discount ?? 0);
            if ($discount > 0) {
                $price = $price - ($price * $discount / 100);
            }
            $price = max(0.0, round($price, 2));
            
            // Add to cart if not already present
            if (!isset($cart[$course->id])) {
                $cart[$course->id] = [
                    'id' => $course->id,
                    'title' => $course->title ?? ($course->name ?? 'Course #'.$course->id),
                    'price' => $price,
                    'image' => $course->image,
                    'qty' => 1,
                ];
                
                Session::put('cart', $cart);
                
                $notify[] = ['success', 'Course added to cart successfully!'];
            } else {
                $notify[] = ['info', 'Course is already in your cart'];
            }
            
            // Redirect to cart page
            return redirect()->route('user.cart.index')->withNotify($notify);
        }

        // User is authenticated - proceed with normal enrollment/payment flow
        $pageTitle = "Enroll Course";
        $price = $course->price;

        if ($course->discount) {
            $price = priceCalculate(@$course->price, @$course->discount);
        }

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();

        return view($this->activeTemplate . 'user.payment.deposit', compact('course', 'pageTitle', 'gatewayCurrency', 'price'));
    }

    public function enrollMultiple()
    {
        $pageTitle = "Enroll Multiple Courses";
        $courseIds = session('enrollment_courses', []);
        
        if (empty($courseIds)) {
            $notify[] = ['error', 'No courses selected for enrollment'];
            return redirect()->route('user.home')->withNotify($notify);
        }
        
        $courses = Course::whereIn('id', $courseIds)->where('status', 1)->get();
        
        if ($courses->isEmpty()) {
            $notify[] = ['error', 'Selected courses are not available'];
            return redirect()->route('user.home')->withNotify($notify);
        }
        
        // Calculate total price
        $totalPrice = 0;
        foreach ($courses as $course) {
            $price = $course->price;
            if ($course->discount) {
                $price = priceCalculate($course->price, $course->discount);
            }
            $totalPrice += $price;
        }
        
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        
        // Clear the enrollment session after getting courses
        session()->forget('enrollment_courses');
        
        return view($this->activeTemplate . 'user.payment.multi_deposit', compact('courses', 'pageTitle', 'gatewayCurrency', 'totalPrice'));
    }
}
