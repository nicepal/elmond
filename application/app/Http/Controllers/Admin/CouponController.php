<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Manage Coupons';
        $coupons = Coupon::query();
        
        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $coupons = $coupons->where(function ($query) use ($search) {
                $query->where('code', 'like', "%$search%")
                      ->orWhere('name', 'like', "%$search%");
            });
        }
        
        $coupons = $coupons->latest()->paginate(getPaginate());
        return view('admin.coupons.index', compact('pageTitle', 'coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        $pageTitle = 'Create Coupon';
        $courses = Course::where('status', 1)->get();
        return view('admin.coupons.create', compact('pageTitle', 'courses'));
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
        ]);

        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->code = strtoupper($request->code);
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_amount = $request->discount_amount;
        $coupon->minimum_purchase = $request->minimum_purchase ?? 0;
        $coupon->starts_at = $request->starts_at;
        $coupon->expires_at = $request->expires_at;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->usage_limit_per_user = $request->usage_limit_per_user;
        $coupon->is_first_purchase_only = $request->has('is_first_purchase_only');
        $coupon->is_registration_bonus = $request->has('is_registration_bonus');
        $coupon->active = $request->has('active');
        
        // Handle applicable courses
        if ($request->has('applicable_courses')) {
            $coupon->applicable_courses = $request->applicable_courses;
        }
        
        $coupon->save();

        $notify[] = ['success', 'Coupon created successfully'];
        return redirect()->route('admin.coupons.index')->withNotify($notify);
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        $pageTitle = 'Edit Coupon';
        $courses = Course::where('status', 1)->get();
        return view('admin.coupons.edit', compact('pageTitle', 'coupon', 'courses'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
        ]);

        $coupon->name = $request->name;
        $coupon->code = strtoupper($request->code);
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_amount = $request->discount_amount;
        $coupon->minimum_purchase = $request->minimum_purchase ?? 0;
        $coupon->starts_at = $request->starts_at;
        $coupon->expires_at = $request->expires_at;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->usage_limit_per_user = $request->usage_limit_per_user;
        $coupon->is_first_purchase_only = $request->has('is_first_purchase_only');
        $coupon->is_registration_bonus = $request->has('is_registration_bonus');
        $coupon->active = $request->has('active');
        
        // Handle applicable courses
        if ($request->has('applicable_courses')) {
            $coupon->applicable_courses = $request->applicable_courses;
        } else {
            $coupon->applicable_courses = null;
        }
        
        $coupon->save();

        $notify[] = ['success', 'Coupon updated successfully'];
        return redirect()->route('admin.coupons.index')->withNotify($notify);
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        $notify[] = ['success', 'Coupon deleted successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Generate a random coupon code.
     */
    public function generateCode()
    {
        $code = strtoupper(Str::random(8));
        return response()->json(['code' => $code]);
    }
}