<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations.
     */
    public function index()
    {
        $pageTitle = 'All Organizations';
        $organizations = $this->organizationData();
        return view('admin.organizations.list', compact('pageTitle', 'organizations'));
    }

    /**
     * Show active organizations.
     */
    public function activeOrganizations()
    {
        $pageTitle = 'Active Organizations';
        $organizations = $this->organizationData('active');
        return view('admin.organizations.list', compact('pageTitle', 'organizations'));
    }

    /**
     * Show inactive organizations.
     */
    public function inactiveOrganizations()
    {
        $pageTitle = 'Inactive Organizations';
        $organizations = $this->organizationData('inactive');
        return view('admin.organizations.list', compact('pageTitle', 'organizations'));
    }

    /**
     * Get organization data with optional scope.
     */
    protected function organizationData($scope = null)
    {
        if ($scope) {
            $organizations = Organization::$scope();
        } else {
            $organizations = Organization::query();
        }

        // Search functionality
        $request = request();
        if ($request->search) {
            $search = $request->search;
            $organizations = $organizations->where(function ($org) use ($search) {
                $org->where('company_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('contact_person_name', 'like', "%$search%");
            });
        }
        
        return $organizations->orderBy('id', 'desc')->paginate(getPaginate());
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        $pageTitle = 'Create Organization';
        $courses = Course::active()->get();
        $countries = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        return view('admin.organizations.create', compact('pageTitle', 'courses', 'countries'));
    }

    /**
     * Store a newly created organization.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email',
            'mobile' => 'required|string|max:20',
            'country_code' => 'required|string|max:10',
            'password' => 'required|string|min:6|same:confirmed',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'designation' => 'nullable|string|max:100',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $organization = Organization::create([
            'company_name' => $request->company_name,
            'contact_person_name' => $request->contact_person_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => $request->country_code,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'password' => Hash::make($request->password),
            'zip' => $request->zip,
            'designation' => $request->designation,
            'status' => 1
        ]);

        // Assign courses if selected
        if ($request->courses) {
            foreach ($request->courses as $courseId) {
                $course = Course::find($courseId);
                if ($course) {
                    $organization->courses()->attach($courseId, [
                        'assigned_price' => $course->price,
                        'assigned_at' => now(),
                        'status' => 1
                    ]);
                }
            }
        }

        $this->sendWelcomeEmail($organization, $request->password);


        $notify[] = ['success', 'Organization created successfully'];
        return to_route('admin.organizations.index')->withNotify($notify);
    }

    /**
     * Display organization details.
     */
    public function show($id)
    {
        $organization = Organization::with(['users', 'courses'])->findOrFail($id);
        $pageTitle = 'Organization Details - ' . $organization->company_name;
        
        $totalEmployees = $organization->users()->count();
        $activeEmployees = $organization->activeUsers()->count();
        $totalCourses = $organization->courses()->count();
        $activeCourses = $organization->activeCourses()->count();
        
        return view('admin.organizations.detail', compact(
            'pageTitle', 'organization', 'totalEmployees', 'activeEmployees', 
            'totalCourses', 'activeCourses'
        ));
    }

    /**
     * Show the form for editing an organization.
     */
    public function edit($id)
    {
        $organization = Organization::with('courses')->findOrFail($id);
        $pageTitle = 'Edit Organization - ' . $organization->company_name;
        $courses = Course::active()->get();
        $countries = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        $assignedCourses = $organization->courses->pluck('id')->toArray();
        
        return view('admin.organizations.edit', compact(
            'pageTitle', 'organization', 'courses', 'countries', 'assignedCourses'
        ));
    }

    /**
     * Update an organization.
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email,' . $id,
            'mobile' => 'required|string|max:20',
            'country_code' => 'required|string|max:10',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'designation' => 'nullable|string|max:100',
            'status' => 'required|in:0,1',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $organization->update([
            'company_name' => $request->company_name,
            'contact_person_name' => $request->contact_person_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => $request->country_code,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'designation' => $request->designation,
            'status' => $request->status
        ]);

        // Update course assignments
        $organization->courses()->detach();
        if ($request->courses) {
            foreach ($request->courses as $courseId) {
                $course = Course::find($courseId);
                if ($course) {
                    $organization->courses()->attach($courseId, [
                        'assigned_price' => $course->price,
                        'assigned_at' => now(),
                        'status' => 1
                    ]);
                }
            }
        }


        $notify[] = ['success', 'Organization updated successfully'];
        return back()->withNotify($notify);
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
                </div>
                
                <!-- Main Content -->
                <div style="padding: 20px; background-color: #ffffff; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                    <div style="margin-bottom: 20px;">
                        <h2 style="color: #333; margin-bottom: 10px;">Hello ' . $user->company_name . '!</h2>
                        <p style="color: #666; font-size: 14px; line-height: 1.5;">We are pleased to inform you that your organization has been successfully registered with:' . gs()->site_name . ' </p>
                    </div>
                    
                    <!-- Login Credentials Box -->
                    <div style="background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin: 20px 0;">
                        <h3 style="color: #333; margin: 0 0 10px 0; font-size: 16px;">Here are your account details for the primary administrator:</h3>
                        <div style="background-color: #fff; padding: 10px; border: 1px solid #eee;">
                            <p style="color: #333; margin: 5px 0; font-size: 14px;"><strong>Email:</strong> ' . $user->email . '</p>
                            <p style="color: #333; margin: 5px 0; font-size: 14px;"><strong>Password:</strong> ' . $password . '</p>
                        </div>
                        <p style="color: #666; font-size: 12px; margin: 10px 0 0 0;">Please change the password upon first login.<br />

You can create additional user accounts for your team under your organization profile.<br />

Keep your login details secure and do not share them outside your organization.<br />
We look forward to supporting your organization on ELMOND.ORG. <br />


</p>
                    </div>
                    
                    <!-- Call to Action -->
                    <div style="text-align: center; margin: 20px 0;">
                        <a href="' . route('instructor.login') . '" style="background-color: #333; color: white; padding: 10px 20px; text-decoration: none; border: 1px solid #333; font-size: 14px;">Login Now</a>
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
                'subject' => 'Welcome to ' . gs()->site_name . ' - Organization Account Created',
                'message' => $emailTemplate
            ], ['email']);
            
            \Log::info('Organization Account Created: ' . $user->email);
            
        } catch (\Exception $e) {
            // Log error but don't stop the process
            \Log::error('Failed to send welcome email to ' . $user->email . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove an organization.
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        
        // Check if organization has active employees
        if ($organization->activeUsers()->count() > 0) {
            $notify[] = ['error', 'Cannot delete organization with active employees'];
            return back()->withNotify($notify);
        }
        
        // Detach all relationships
        $organization->users()->detach();
        $organization->courses()->detach();
        
        $organization->delete();
        
        $notify[] = ['success', 'Organization deleted successfully'];
        return back()->withNotify($notify);
    }
}
