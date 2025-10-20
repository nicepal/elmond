<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $organization = Auth::guard('organization')->user();
        
        $employees = $organization->users()
                                 ->when($request->search, function($query, $search) {
                                     $query->where(function($q) use ($search) {
                                         $q->where('firstname', 'like', "%{$search}%")
                                           ->orWhere('lastname', 'like', "%{$search}%")
                                           ->orWhere('email', 'like', "%{$search}%")
                                           ->orWhere('mobile', 'like', "%{$search}%");
                                     });
                                 })
                                 ->when($request->status !== null, function($query) use ($request) {
                                     $query->wherePivot('status', $request->status);
                                 })
                                 ->withCount('enrollments')
                                 ->orderBy('organization_users.created_at', 'desc')
                                 ->paginate(15);
        
        $pageTitle = 'Manage Employees';
        
        return view('organization.employees.index', compact('pageTitle', 'employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $organization = Auth::guard('organization')->user();
        $countries = Country::all();
        $courses = $organization->activeCourses()->get();
        $pageTitle = 'Add New Employee';
        
        return view('organization.employees.create', compact('pageTitle', 'countries', 'courses'));
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $organization = Auth::guard('organization')->user();
        
        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:40',
            'country_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:40',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);
        
        // Generate unique username
        $baseUsername = strtolower(preg_replace('/[^a-z0-9_]/', '', $request->firstname . $request->lastname));
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => $request->country_code,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'status' => 1,
            'ev' => 1, // Email verified
            'sv' => 1, // SMS verified
        ]);
        
        // Attach user to organization
        $organization->users()->attach($user->id, [
            'joined_at' => now(),
            'status' => 1
        ]);
        
        // Enroll user in selected courses
        if ($request->courses) {
            foreach ($request->courses as $courseId) {
                // Verify the course belongs to this organization
                $course = $organization->courses()->where('courses.id', $courseId)->first();
                if ($course) {
                    // Check if already enrolled
                    $existingEnroll = \App\Models\Enroll::where('user_id', $user->id)
                                                      ->where('course_id', $courseId)
                                                      ->first();
                    
                    if (!$existingEnroll) {
                        $enroll = new \App\Models\Enroll();
                        $enroll->user_id = $user->id;
                        $enroll->course_id = $courseId;
                        $enroll->name = $course->name;
                        $enroll->deposit_id = 0;
                        $enroll->owner_id = $course->owner_id;
                        $enroll->owner_type = $course->owner_type;
                        $enroll->price = $course->pivot->assigned_price ?? $course->price;
                        $enroll->discount = $course->discount ?? 0;
                        $enroll->total_amount = ($course->pivot->assigned_price ?? $course->price) - ($course->discount ?? 0);
                        $enroll->status = 1; // Approved
                        $enroll->save();
                    }
                }
            }
        }
        
        return redirect()->route('organization.employees.index')
                        ->with('success', 'Employee added successfully and enrolled in selected courses!');
    }

    /**
     * Display the specified employee.
     */
    public function show($id)
    {
        $organization = Auth::guard('organization')->user();
        
        $employee = $organization->users()
                                ->where('users.id', $id)
                                ->withCount(['enrollments', 'completedCourses'])
                                ->firstOrFail();
        
        $enrollments = $employee->enrollments()
                               ->whereHas('course', function($query) use ($organization) {
                                   $query->whereHas('organizations', function($q) use ($organization) {
                                       $q->where('organizations.id', $organization->id);
                                   });
                               })
                               ->with('course')
                               ->latest()
                               ->paginate(10);
        
        $pageTitle = 'Employee Details';
        
        return view('organization.employees.show', compact('pageTitle', 'employee', 'enrollments'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit($id)
    {
        $organization = Auth::guard('organization')->user();
        
        $employee = $organization->users()
                                ->where('users.id', $id)
                                ->firstOrFail();
        
        $countries = Country::all();
        $courses = $organization->activeCourses()->get();
        
        // Get current enrollments for this employee in organization courses
        $currentEnrollments = $employee->enrollments()
                                     ->whereHas('course', function($query) use ($organization) {
                                         $query->whereHas('organizations', function($q) use ($organization) {
                                             $q->where('organizations.id', $organization->id);
                                         });
                                     })
                                     ->pluck('course_id')
                                     ->toArray();
        
        $pageTitle = 'Edit Employee';
        
        return view('organization.employees.edit', compact('pageTitle', 'employee', 'countries', 'courses', 'currentEnrollments'));
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, $id)
    {
        $organization = Auth::guard('organization')->user();
        
        $employee = $organization->users()
                                ->where('users.id', $id)
                                ->firstOrFail();
        
        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => ['required', 'email', Rule::unique('users')->ignore($employee->id)],
            'mobile' => 'required|string|max:40',
            'country_code' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:40',
            'status' => 'required|boolean',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);
        
        $updateData = $request->only([
            'firstname', 'lastname', 'email', 'mobile', 'country_code',
            'address', 'city', 'state', 'zip', 'status'
        ]);
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        $employee->update($updateData);
        
        // Update pivot table status
        $organization->users()->updateExistingPivot($employee->id, [
            'status' => $request->status
        ]);
        
        // Handle course enrollments
        $selectedCourses = $request->courses ?? [];
        
        // Get current enrollments for organization courses
        $currentEnrollments = $employee->enrollments()
                                     ->whereHas('course', function($query) use ($organization) {
                                         $query->whereHas('organizations', function($q) use ($organization) {
                                             $q->where('organizations.id', $organization->id);
                                         });
                                     })
                                     ->get();
        
        $currentCourseIds = $currentEnrollments->pluck('course_id')->toArray();
        
        // Remove enrollments that are no longer selected
        $toRemove = array_diff($currentCourseIds, $selectedCourses);
        foreach ($toRemove as $courseId) {
            $enrollment = $currentEnrollments->where('course_id', $courseId)->first();
            if ($enrollment) {
                $enrollment->delete();
            }
        }
        
        // Add new enrollments
        $toAdd = array_diff($selectedCourses, $currentCourseIds);
        foreach ($toAdd as $courseId) {
            $course = $organization->courses()->where('courses.id', $courseId)->first();
            if ($course) {
                $enroll = new \App\Models\Enroll();
                $enroll->user_id = $employee->id;
                $enroll->course_id = $courseId;
                $enroll->name = $course->name;
                $enroll->deposit_id = 0;
                $enroll->owner_id = $course->owner_id;
                $enroll->owner_type = $course->owner_type;
                $enroll->price = $course->pivot->assigned_price ?? $course->price;
                $enroll->discount = $course->discount ?? 0;
                $enroll->total_amount = ($course->pivot->assigned_price ?? $course->price) - ($course->discount ?? 0);
                $enroll->status = 1; // Approved
                $enroll->save();
            }
        }
        
        return redirect()->route('organization.employees.index')
                        ->with('success', 'Employee updated successfully and course enrollments updated!');
    }

    /**
     * Remove the specified employee from organization.
     */
    public function destroy($id)
    {
        $organization = Auth::guard('organization')->user();
        
        $employee = $organization->users()
                                ->where('users.id', $id)
                                ->firstOrFail();
        
        // Detach from organization (don't delete the user)
        $organization->users()->detach($employee->id);
        
        return redirect()->route('organization.employees.index')
                        ->with('success', 'Employee removed from organization successfully!');
    }
    
    /**
     * Remove employee enrollment from a specific course.
     */
    public function destroyEnrollment($employeeId, $enrollmentId)
    {
        $organization = Auth::guard('organization')->user();
        
        // Verify the employee belongs to this organization
        $employee = $organization->users()
                                ->where('users.id', $employeeId)
                                ->firstOrFail();
        
        // Find and delete the enrollment
        $enrollment = \App\Models\Enroll::where('id', $enrollmentId)
                                        ->where('user_id', $employeeId)
                                        ->firstOrFail();
        
        $enrollment->delete();
        
        return redirect()->back()
                        ->with('success', 'Employee enrollment removed successfully!');
    }
}
