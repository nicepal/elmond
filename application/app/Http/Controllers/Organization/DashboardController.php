<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the organization dashboard.
     */
    public function index()
    {
        $organization = Auth::guard('organization')->user();
        
        // Get organization statistics
        $totalEmployees = $organization->users()->count();
        $activeEmployees = $organization->users()->wherePivot('status', 1)->count();
        $totalCourses = $organization->courses()->count();
        
        // Get enrollment statistics
        $employeeIds = $organization->users()->limit(100)->pluck('users.id');
        $totalEnrollments = Enroll::whereIn('user_id', $employeeIds)->count();
        $completedEnrollments = Enroll::whereIn('user_id', $employeeIds)
                                        ->where('status', 1)
                                        ->count();
        
        // Recent employees
        $recentEmployees = $organization->users()
                                      ->select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'organization_users.created_at')
                                      ->orderBy('organization_users.created_at', 'desc')
                                      ->limit(5)
                                      ->get();
        
        // Course progress data (simplified to avoid memory issues)
        $courseProgress = $organization->courses()
                                     ->select('courses.id', 'courses.name')
                                     ->limit(5)
                                     ->get();
        
        // Add enrollment counts manually to avoid memory issues
        foreach ($courseProgress as $course) {
            $course->enrollments_count = 0;
            $course->completed_count = 0;
        }
        
        $pageTitle = 'Organization Dashboard';
        
        return view('organization.dashboard.index', compact(
            'pageTitle',
            'organization',
            'totalEmployees',
            'activeEmployees', 
            'totalCourses',
            'totalEnrollments',
            'completedEnrollments',
            'recentEmployees',
            'courseProgress'
        ));
    }
    
    /**
     * Show organization profile.
     */
    public function profile()
    {
        $organization = Auth::guard('organization')->user();
        $pageTitle = 'Organization Profile';
        
        return view('organization.profile', compact('pageTitle', 'organization'));
    }
    
    /**
     * Update organization profile.
     */
    public function updateProfile(Request $request)
    {
        $organization = Auth::guard('organization')->user();
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email,' . $organization->id,
            'designation' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
        ]);
        
        $organization->update($request->only([
            'company_name',
            'contact_person_name', 
            'email',
            'designation',
            'mobile',
            'address',
            'city',
            'state',
            'zip'
        ]));
        
        return back()->with('success', 'Profile updated successfully!');
    }
}
