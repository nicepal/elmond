<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Course;
use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManageUsersController extends Controller
{

    public function allUsers()
    {
        $pageTitle = 'All Users';
        $users = $this->userData();
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function activeUsers()
    {
        $pageTitle = 'Active Users';
        $users = $this->userData('active');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $users = $this->userData('banned');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $users = $this->userData('emailUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycUnverifiedUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users = $this->userData('kycUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycPendingUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users = $this->userData('kycPending');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $users = $this->userData('emailVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function mobileUnverifiedUsers()
    {
        $pageTitle = 'Mobile Unverified Users';
        $users = $this->userData('mobileUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function mobileVerifiedUsers()
    {
        $pageTitle = 'Mobile Verified Users';
        $users = $this->userData('mobileVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function usersWithBalance()
    {
        $pageTitle = 'Users with Balance';
        $users = $this->userData('withBalance');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    protected function userData($scope = null){
        if ($scope) {
            $users = User::$scope();
        }else{
            $users = User::query();
        }

        //search
        $request = request();
        if ($request->search) {
            $search = $request->search;
            $users  = $users->where(function ($user) use ($search) {
                            $user->where('username', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                      });
        }
        return $users->orderBy('id','desc')->paginate(getPaginate());
    }


    public function detail($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'User Details / @'.$user->username;

        $totalDeposit = Deposit::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalWithdrawals = Withdrawal::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalTransaction = Transaction::where('user_id',$user->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        return view('admin.users.detail', compact('pageTitle', 'user','totalDeposit','totalWithdrawals','totalTransaction','countries'));
    }


    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $user = User::findOrFail($id);
        return view('admin.users.kyc_detail', compact('pageTitle','user'));
    }

    public function kycApprove($id)
    {
        $user = User::findOrFail($id);
        $user->kv = 1;
        $user->save();

        notify($user,'KYC_APPROVE',[]);

        $notify[] = ['success','KYC approved successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function kycReject($id)
    {
        $user = User::findOrFail($id);
        foreach ($user->kyc_data as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify').'/'.$kycData->value);
            }
        }
        $user->kv = 0;
        $user->kyc_data = null;
        $user->save();

        notify($user,'KYC_REJECT',[]);

        $notify[] = ['success','KYC rejected successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:40|unique:users,mobile,' . $user->id,
            'country' => 'required|in:'.$countries,
        ]);
        $user->mobile = $dialCode.$request->mobile;
        $user->country_code = $countryCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$country,
                        ];
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->ts = $request->ts ? 1 : 0;
        if (!$request->kv) {
            $user->kv = 0;
            if ($user->kyc_data) {
                foreach ($user->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify').'/'.$kycData->value);
                    }
                }
            }
            $user->kyc_data = null;
        }else{
            $user->kv = 1;
        }
        $user->save();

        $notify[] = ['success', 'User details has been updated successfully'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $amount = $request->amount;
        $general = gs();
        $trx = getTrx();

        $transaction = new Transaction();

        if ($request->act == 'add') {
            $user->balance += $amount;

            $transaction->trx_type = '+';
            $transaction->remark = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', $general->cur_sym . $amount . ' has been added successfully'];

        } else {
            if ($amount > $user->balance) {
                $notify[] = ['error', $user->username . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $user->balance -= $amount;

            $transaction->trx_type = '-';
            $transaction->remark = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[] = ['success', $general->cur_sym . $amount . ' subtracted successfully'];
        }

        $user->save();

        $transaction->user_id = $user->id;
        $transaction->amount = $amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = 0;
        $transaction->trx =  $trx;
        $transaction->details = $request->remark;
        $transaction->save();

        notify($user, $notifyTemplate, [
            'trx' => $trx,
            'amount' => showAmount($amount),
            'remark' => $request->remark,
            'post_balance' => showAmount($user->balance)
        ]);

        return back()->withNotify($notify);
    }

    public function login($id){
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request,$id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 1) {
            $request->validate([
                'reason'=>'required|string|max:255'
            ]);
            $user->status = 0;
            $user->ban_reason = $request->reason;
            $notify[] = ['success','User banned successfully'];
        }else{
            $user->status = 1;
            $user->ban_reason = null;
            $notify[] = ['success','User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);

    }


    public function showNotificationSingleForm($id)
    {
        $user = User::findOrFail($id);
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning','Notification options are disabled currently'];
            return to_route('admin.users.detail',$user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        notify($user,'DEFAULT',[
            'subject'=>$request->subject,
            'message'=>$request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning','Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $users = User::where('ev',1)->where('sv',1)->where('status',1)->count();
        $pageTitle = 'Notification to Verified Users';
        return view('admin.users.notification_all', compact('pageTitle','users'));
    }

    public function sendNotificationAll(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'message' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $user = User::where('status', 1)->where('ev',1)->where('sv',1)->skip($request->skip)->first();

        notify($user,'DEFAULT',[
            'subject'=>$request->subject,
            'message'=>$request->message,
        ]);

        return response()->json([
            'success'=>'message sent',
            'total_sent'=>$request->skip + 1,
        ]);
    }

    public function notificationLog($id){
        $user = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to '.$user->username;
        $logs = NotificationLog::where('user_id',$id)->with('user')->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle','logs','user'));
    }

    public function showCreateStudentForm()
    {
        $pageTitle = 'Create New Student';
        $countries = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        $courses = Course::where('status', 1)->get(); // Get active courses
        return view('admin.users.create_student', compact('pageTitle', 'countries', 'courses'));
    }

    public function createStudent(Request $request)
    {
        $countryData = json_decode(file_get_contents(resource_path('views/includes/country.json')));
        $countryArray = (array)$countryData;
        $countries = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country = $countryData->$countryCode->country;
        $dialCode = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'username' => 'required|string|max:40|unique:users,username',
            'email' => 'required|email|string|max:40|unique:users,email',
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:'.$countries,
            'password' => 'required|string|min:6',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id'
        ]);

        // Check if mobile number already exists
        $fullMobile = $dialCode . $request->mobile;
        $existingUser = User::where('mobile', $fullMobile)->first();
        if ($existingUser) {
            $notify[] = ['error', 'Mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        // Create the user
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->country_code = $countryCode;
        $user->mobile = $fullMobile;
        $user->password = Hash::make($request->password);
        $user->address = [
            'country' => $country,
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city
        ];
        $user->status = 1; // Active
        $user->ev = 1; // Email verified
        $user->sv = 1; // SMS verified
        $user->ts = 0; // Two factor disabled
        $user->tv = 1; // Two factor verified
        $user->save();

        // Enroll in selected courses
        if ($request->courses && count($request->courses) > 0) {
            foreach ($request->courses as $courseId) {
                $course = Course::find($courseId);
                if ($course) {
                    $enroll = new Enroll();
                    $enroll->user_id = $user->id;
                    $enroll->course_id = $courseId;
                    $enroll->name = $course->name; // Add this line
                    $enroll->deposit_id = 0; // No deposit for offline payment
                    $enroll->owner_id = $course->owner_id;
                    $enroll->owner_type = $course->owner_type;
                    $enroll->price = $course->price;
                    $enroll->discount = $course->discount ?? 0;
                    $enroll->total_amount = $course->price - ($course->discount ?? 0);
                    $enroll->status = 1; // Approved
                    $enroll->save();
                }
            }
        }

        // Send email notification with login credentials
        $this->sendWelcomeEmail($user, $request->password);

        $notify[] = ['success', 'Student created successfully and enrolled in selected courses'];
        return redirect()->route('admin.users.detail', $user->id)->withNotify($notify);
    }

    public function showEnrollCoursesForm($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Enroll ' . $user->fullname . ' in Courses';
        
        // Get courses that user is not already enrolled in
        $enrolledCourseIds = Enroll::where('user_id', $user->id)->pluck('course_id')->toArray();
        $courses = Course::where('status', 1)
                        ->whereNotIn('id', $enrolledCourseIds)
                        ->get();
        
        return view('admin.users.enroll_courses', compact('pageTitle', 'user', 'courses'));
    }

    public function enrollStudentInCourses(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'courses' => 'required|array|min:1',
            'courses.*' => 'exists:courses,id'
        ]);
    
        $enrolledCount = 0;
        foreach ($request->courses as $courseId) {
            // Check if already enrolled
            $existingEnroll = Enroll::where('user_id', $user->id)
                                  ->where('course_id', $courseId)
                                  ->first();
            
            if (!$existingEnroll) {
                $course = Course::find($courseId);
                if ($course) {
                    // Debug: Check if course name exists
                    \Log::info('Course data:', ['id' => $course->id, 'name' => $course->name]);
                    
                    $enroll = new Enroll();
                    $enroll->user_id = $user->id;
                    $enroll->course_id = $courseId;
                    $enroll->name = $course->name; // Make sure this is not null
                    $enroll->deposit_id = 0;
                    $enroll->owner_id = $course->owner_id;
                    $enroll->owner_type = $course->owner_type;
                    $enroll->price = $course->price;
                    $enroll->discount = $course->discount ?? 0;
                    $enroll->total_amount = $course->price - ($course->discount ?? 0);
                    $enroll->status = 1;
                    
                    // Debug: Check enroll data before save
                    \Log::info('Enroll data before save:', $enroll->toArray());
                    
                    $enroll->save();
                    $enrolledCount++;
                }
            }
        }
    
        $notify[] = ['success', "Student enrolled in {$enrolledCount} course(s) successfully"];
        return redirect()->route('admin.users.detail', $user->id)->withNotify($notify);
    }

    private function sendWelcomeEmail($user, $password, $enrolledCourses = [])
    {
        try {
            // Get enrolled courses for this user
            $userEnrollments = Enroll::where('user_id', $user->id)
                ->with('course')
                ->get();
            
            // Build course list HTML
            $courseListHtml = '';
            if ($userEnrollments->count() > 0) {
                $courseListHtml = '<div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">';
                $courseListHtml .= '<h4 style="color: #2c3e50; margin-bottom: 15px;">📚 Your Enrolled Courses:</h4>';
                $courseListHtml .= '<ul style="list-style: none; padding: 0;">';
                
                foreach ($userEnrollments as $enrollment) {
                    if ($enrollment->course) {
                        $courseListHtml .= '<li style="background: white; margin: 8px 0; padding: 12px; border-left: 4px solid #3498db; border-radius: 4px;">';
                        $courseListHtml .= '<strong style="color: #2c3e50;">' . $enrollment->course->name . '</strong>';
                        if ($enrollment->course->price > 0) {
                            $courseListHtml .= '<span style="color: #27ae60; float: right; font-weight: bold;">' . gs()->cur_sym . number_format($enrollment->course->price, 2) . '</span>';
                        } else {
                            $courseListHtml .= '<span style="color: #27ae60; float: right; font-weight: bold;">FREE</span>';
                        }
                        $courseListHtml .= '</li>';
                    }
                }
                
                $courseListHtml .= '</ul></div>';
            }
            
            // Enhanced email template with professional styling
            $emailTemplate = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff;">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                    <h1 style="color: white; margin: 0; font-size: 28px; font-weight: bold;">🎉 Welcome to ' . gs()->site_name . '!</h1>
                    <p style="color: #f1f2f6; margin: 10px 0 0 0; font-size: 16px;">Your learning journey starts here</p>
                </div>
                
                <!-- Main Content -->
                <div style="padding: 30px; background-color: #ffffff;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h2 style="color: #2c3e50; margin-bottom: 10px;">Hello ' . $user->fullname . '! 👋</h2>
                        <p style="color: #7f8c8d; font-size: 16px; line-height: 1.6;">Your student account has been created successfully. We\'re excited to have you join our learning community!</p>
                    </div>
                    
                    <!-- Login Credentials Box -->
                    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 10px; margin: 25px 0; text-align: center;">
                        <h3 style="color: white; margin-bottom: 20px; font-size: 20px;">🔐 Your Login Credentials</h3>
                        <div style="background: rgba(255,255,255,0.9); padding: 20px; border-radius: 8px; margin: 15px 0;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 8px; text-align: left; font-weight: bold; color: #2c3e50;">👤 Username:</td>
                                    <td style="padding: 8px; text-align: right; color: #2c3e50; font-family: monospace; background: #ecf0f1; border-radius: 4px;">' . $user->username . '</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px; text-align: left; font-weight: bold; color: #2c3e50;">📧 Email:</td>
                                    <td style="padding: 8px; text-align: right; color: #2c3e50; font-family: monospace; background: #ecf0f1; border-radius: 4px;">' . $user->email . '</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px; text-align: left; font-weight: bold; color: #2c3e50;">🔑 Password:</td>
                                    <td style="padding: 8px; text-align: right; color: #e74c3c; font-family: monospace; background: #ecf0f1; border-radius: 4px; font-weight: bold;">' . $password . '</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    ' . $courseListHtml . '
                    
                    <!-- Action Button -->
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . route('user.login') . '" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">🚀 Start Learning Now</a>
                    </div>
                    
                    <!-- Security Notice -->
                    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 20px 0;">
                        <p style="margin: 0; color: #856404; font-size: 14px;">🔒 <strong>Security Tip:</strong> Please change your password after your first login for better security.</p>
                    </div>
                    
                    <!-- Support Section -->
                    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #ecf0f1;">
                        <h4 style="color: #2c3e50; margin-bottom: 10px;">Need Help? 🤝</h4>
                        <p style="color: #7f8c8d; margin: 5px 0;">Our support team is here to help you succeed!</p>
                        <p style="color: #7f8c8d; margin: 5px 0;">📧 Email: ' . gs()->email_from . '</p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="background-color: #2c3e50; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;">
                    <p style="color: #bdc3c7; margin: 0; font-size: 14px;">Best regards,<br><strong style="color: white;">' . gs()->site_name . ' Team</strong></p>
                    <p style="color: #7f8c8d; margin: 10px 0 0 0; font-size: 12px;">© ' . date('Y') . ' ' . gs()->site_name . '. All rights reserved.</p>
                </div>
            </div>';
            
            // Send the enhanced email
            notify($user, 'DEFAULT', [
                'subject' => '🎉 Welcome to ' . gs()->site_name . ' - Your Account is Ready!',
                'message' => $emailTemplate
            ], ['email']);
            
            \Log::info('Welcome email sent successfully to: ' . $user->email);
            
        } catch (\Exception $e) {
            // Log error but don't stop the process
            \Log::error('Failed to send welcome email to ' . $user->email . ': ' . $e->getMessage());
        }
    }
}
