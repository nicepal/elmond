<?php

use Illuminate\Support\Facades\Route;


Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return redirect()->back();
})->name('clear.cache');

Route::namespace('Auth')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->name('logout');
    });

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request/report', 'requestReport')->name('request.report');
        Route::post('request/report', 'reportSubmit');
        Route::get('download/attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });


    // Category Manager
    Route::controller('CategoryController')->name('category.')->prefix('manage/category')->group(function () {
        Route::get('/', 'categories')->name('all');
        Route::post('create', 'categoryStore')->name('store');
        Route::put('update/{category}', 'categoryUpdate')->name('update');
    });

    // Add Group Manager
    Route::controller('GroupController')->name('group.')->prefix('manage/group')->group(function () {
        Route::get('/', 'groups')->name('all');
        Route::post('create', 'store')->name('store');
        Route::put('update/{group}', 'update')->name('update');
    });

    // Course Manager
    //Course Management
    Route::controller('CourseController')->name('course.')->group(function () {
        Route::get('course/all', 'index')->name('index');
        Route::get('course/instructor/', 'instructorCourses')->name('instructor');
        Route::post('course/instructor/approved/{id}', 'adminStatusApproved')->name('instructor.approved');
        
        // New launch routes
        Route::get('course/new-launches', 'newLaunches')->name('new.launches');
        Route::get('course/upcoming', 'upcomingCourses')->name('upcoming');
        Route::get('course/featured', 'featuredCourses')->name('featured');
        
        Route::get('course/create', 'create')->name('create');
        Route::post('course/store', 'store')->name('store');
        Route::get('course/edit/{id}', 'edit')->name('edit');
        Route::put('course/update/{id}', 'update')->name('update');
    });

    Route::get('lesson/get-modules-by-course/{courseId}', [App\Http\Controllers\Admin\LessonController::class, 'getModulesByCourse'])->name('lesson.getModulesByCourse');
    //Upload Lesson 
    Route::controller('LessonController')->name('lesson.')->group(function () {
        Route::get('course/{course_id}', 'courses')->name('courses');
        Route::get('lessons-instructor/{course_id}', 'instructorLessons')->name('instructor');

        Route::get('lesson/create/{id?}', 'create')->name('create');
        Route::post('lesson/store', 'store')->name('store');
        Route::get('lesson/edit/{id}', 'edit')->name('edit');
        Route::post('lesson/update/{id}', 'update')->name('update');
        Route::post('lesson/delete/{id}', 'lessonDelete')->name('delete');
        Route::post('lesson/video-upload', 'videoUpload')->name('video.upload');
        Route::post('lesson/video-upload-delete', 'videoUploadDelete')->name('video.upload.delete');

        Route::post('lesson/edit/video/upload', 'editVideoUpload')->name('edit.video.upload');
        Route::post('lesson/edit-video-upload-delete', 'editVideoUploadDelete')->name('edit.video.upload.delete');

        Route::post('course/group', 'CourseGroup')->name('course.group');

        Route::post('lesson/document-delete/{id}', 'documentDelete')->name('document.delete');
    });

    // Zoom credentials
    Route::controller('ZoomController')->name('zoom.')->group(function () {
        Route::get('setup-credential', 'zoomCredential')->name('credential');
        Route::post('setup-credential', 'zoomCredentialStore')->name('store.credential');
    });


    // Quizzes Manager
    Route::controller('QuizController')->name('quiz.')->prefix('quiz/')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('instructor', 'instructorQuiz')->name('instructor');
        Route::get('instructor/student/participants/{id}', 'instructorStudentParticipants')->name('instructor.participants');
        Route::post('instructor/student/delete/{quiz_id}/{user_id}', 'instructorParticipantDelete')->name('instructor.participant.delete');
        Route::get('student/participants/{id}', 'participants')->name('participants');
        Route::post('delete/{quiz_id}/{user_id}', 'participantDelete')->name('participant.delete');
    });

 
     // Question Manager
     Route::controller('QuestionController')->name('question.')->prefix('question/')->group(function () {
        Route::get('/{id}', 'index')->name('index');
        Route::get('create/{id}', 'create')->name('create');
        Route::post('store/{id}', 'store')->name('store');
        Route::get('edit/{question_id}/{quiz_id}', 'edit')->name('edit');
        Route::put('update/{question_id}/{quiz_id}', 'update')->name('update');
        Route::post('delete/{question_id}/{quiz_id}', 'delete')->name('delete');
        Route::get('instructor/{question_id}', 'instructorQuestion')->name('instructor');
        Route::post('participant_student', 'participant_student')->name('participant_student');
  
    });


    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('manage/users')->group(function () {
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email/verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email/unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile/unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('mobile/verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('mobile/verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('with/balance', 'usersWithBalance')->name('with.balance');

        // Add these new routes for student creation and enrollment
        Route::get('create-student', 'showCreateStudentForm')->name('create.student');
        Route::post('create-student', 'createStudent')->name('create.student.store');
        Route::get('enroll-courses/{id}', 'showEnrollCoursesForm')->name('enroll.courses');
        Route::post('enroll-courses/{id}', 'enrollStudentInCourses')->name('enroll.courses.store');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add/sub/balance/{id}', 'addSubBalance')->name('add.sub.balance');
        Route::get('send/notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send/notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('send/notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send/notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('notification/log/{id}', 'notificationLog')->name('notification.log');

        // kyc
        Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
        Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
    });

    //KYC setting
    Route::controller('KycController')->group(function () {
        Route::get('kyc-setting', 'setting')->name('kyc.setting');
        Route::post('kyc-setting', 'settingUpdate');

        // instructor
        Route::get('instructor/kyc-setting', 'instructorSetting')->name('instructor.kyc.setting');
        Route::post('instructor/kyc-setting', 'instructorSettingUpdate')->name('instructor.kyc.setting');
    });


    // Instructor Manager
    Route::controller('ManageInstructorsController')->name('instructors.')->prefix('manage/instructors')->group(function () {
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email/verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email/unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile/unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('mobile/verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('mobile/verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('with/balance', 'usersWithBalance')->name('with.balance');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add/sub/balance/{id}', 'addSubBalance')->name('add.sub.balance');
        Route::get('send/notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send/notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('send/notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send/notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('notification/log/{id}', 'notificationLog')->name('notification.log');

        // kyc
        Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
        Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
    });

    // Organization Manager
    Route::controller('OrganizationController')->name('organizations.')->prefix('manage/organizations')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('assign-courses/{id}', 'assignCourses')->name('assign.courses');
        Route::post('remove-course/{organization_id}/{course_id}', 'removeCourse')->name('remove.course');
    });


     // Ad System
     Route::controller('CertificateController')->name('certificate.')->prefix('certificate')->group(function () {
        Route::get('/edit', 'templateEdit')->name('edit');
        Route::post('/update', 'templateUpdate')->name('update');
        Route::get('/students', 'all')->name('all');
    });


    // Subscriber
    Route::controller('SubscriberController')->group(function () {
        Route::get('subscriber', 'index')->name('subscriber.index');
        Route::get('subscriber/send/email', 'sendEmailForm')->name('subscriber.send.email');
        Route::post('subscriber/remove/{id}', 'remove')->name('subscriber.remove');
        Route::post('subscriber/send/email', 'sendEmail')->name('subscriber.send.email');
    });


    // Deposit Gateway
    Route::name('gateway.')->prefix('payment/gateways')->group(function () {
        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->group(function () {
            Route::get('automatic', 'index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'update')->name('automatic.update');
            Route::post('automatic/remove/{id}', 'remove')->name('automatic.remove');
            Route::post('automatic/activate/{code}', 'activate')->name('automatic.activate');
            Route::post('automatic/deactivate/{code}', 'deactivate')->name('automatic.deactivate');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->group(function () {
            Route::get('manual', 'index')->name('manual.index');
            Route::get('manual/new', 'create')->name('manual.create');
            Route::post('manual/new', 'store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'update')->name('manual.update');
            Route::post('manual/activate/{code}', 'activate')->name('manual.activate');
            Route::post('manual/deactivate/{code}', 'deactivate')->name('manual.deactivate');
        });
    });


    // DEPOSIT SYSTEM
    Route::name('deposit.')->controller('DepositController')->prefix('manage/deposits')->group(function () {
        Route::get('/', 'deposit')->name('list');
        Route::get('pending', 'pending')->name('pending');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('approved', 'approved')->name('approved');
        Route::get('successful', 'successful')->name('successful');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');

        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });


    // WITHDRAW SYSTEM
    Route::name('withdraw.')->prefix('manage/withdrawals')->group(function () {

        Route::controller('WithdrawalController')->group(function () {
            Route::get('pending', 'pending')->name('pending');
            Route::get('approved', 'approved')->name('approved');
            Route::get('rejected', 'rejected')->name('rejected');
            Route::get('log', 'log')->name('log');
            Route::get('details/{id}', 'details')->name('details');
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });


        // Withdraw Method
        Route::controller('WithdrawMethodController')->group(function () {
            Route::get('method/', 'methods')->name('method.index');
            Route::get('method/create', 'create')->name('method.create');
            Route::post('method/create', 'store')->name('method.store');
            Route::get('method/edit/{id}', 'edit')->name('method.edit');
            Route::post('method/edit/{id}', 'update')->name('method.update');
            Route::post('method/activate/{id}', 'activate')->name('method.activate');
            Route::post('method/deactivate/{id}', 'deactivate')->name('method.deactivate');
        });
    });

    // Report
    Route::controller('ReportController')->group(function () {
        Route::get('report/transaction', 'transaction')->name('report.transaction');
        Route::get('report/login/history', 'loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/notification/history', 'notificationHistory')->name('report.notification.history');
        Route::get('report/email/detail/{id}', 'emailDetails')->name('report.email.details');


        // instructor
        Route::get('instructor/report/transaction', 'instructorTransaction')->name('instructor.report.transaction');
        Route::get('instructor/report/login/history', 'instructorLoginHistory')->name('instructor.report.login.history');
        Route::get('instructor/report/login/ipHistory/{ip}', 'instructorLoginIpHistory')->name('instructor.report.login.ipHistory');
        Route::get('instructor/report/notification/history', 'instructorNotificationHistory')->name('instructor.report.notification.history');
        Route::get('instructor/report/email/detail/{id}', 'instructorEmailDetails')->name('instructor.report.email.details');
    });


    // Admin Support
    Route::controller('SupportTicketController')->prefix('support')->group(function () {
        Route::get('tickets', 'tickets')->name('ticket');
        Route::get('tickets/pending', 'pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'replyTicket')->name('ticket.reply');
        Route::post('ticket/close/{id}', 'closeTicket')->name('ticket.close');
        Route::get('ticket/download/{ticket}', 'ticketDownload')->name('ticket.download');
        Route::post('ticket/delete/{id}', 'ticketDelete')->name('ticket.delete');


        // instructor
        Route::get('instructor/tickets', 'instructorTickets')->name('instructor.ticket');
        Route::get('instructor/tickets/pending', 'instructorPendingTicket')->name('instructor.ticket.pending');
        Route::get('instructor/tickets/closed', 'instructorClosedTicket')->name('instructor.ticket.closed');
        Route::get('instructor/tickets/answered', 'instructorAnsweredTicket')->name('instructor.ticket.answered');
        Route::get('instructor/tickets/view/{id}', 'ticketReply')->name('instructor.ticket.view');

    });


    // Language Manager
    Route::controller('LanguageController')->prefix('manage')->group(function () {
        Route::get('languages', 'langManage')->name('language.manage');
        Route::post('language', 'langStore')->name('language.manage.store');
        Route::post('language/delete/{id}', 'langDelete')->name('language.manage.delete');
        Route::post('language/update/{id}', 'langUpdate')->name('language.manage.update');
        Route::get('language/edit/{id}', 'langEdit')->name('language.key');
        Route::post('language/import', 'langImport')->name('language.import.lang');
        Route::post('language/store/key/{id}', 'storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'updateLanguageJson')->name('language.update.key');
    });

    Route::controller('GeneralSettingController')->group(function () {
        // General Setting
        Route::get('global/settings', 'index')->name('setting.index');
        Route::post('global/settings', 'update')->name('setting.update');

        //configuration
        Route::post('setting/system-configuration', 'systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo', 'logoIconUpdate')->name('setting.logo.icon');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie', 'cookieSubmit');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit');


        //socialite credentials
        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');


         //instructor socialite credentials
         Route::get('setting/instructor/social/credentials', 'instructorSocialiteCredentials')->name('setting.instructor.socialite.credentials');
         Route::post('setting/instructor/social/credentials/update/{key}', 'instructorUpdateSocialiteCredential')->name('setting.instructor.socialite.credentials.update');
         Route::post('setting/instructor/social/credentials/status/{key}', 'instructorUpdateSocialiteCredentialStatus')->name('setting.instructor.socialite.credentials.status.update');

    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notifications')->group(function () {
        //Template Setting
        Route::get('global', 'global')->name('global');
        Route::post('global/update', 'globalUpdate')->name('global.update');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');
    });



    // Ad System
    Route::controller('AdController')->name('ad.')->prefix('ad')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
    });



    // Plugin
    Route::controller('ExtensionController')->group(function () {
        Route::get('extensions', 'index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'update')->name('extensions.update');
        Route::post('extensions/status/{id}', 'status')->name('extensions.status');
    });
    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');

    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function () {
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->prefix('manage')->group(function () {
            Route::get('pages', 'managePages')->name('manage.pages');
            Route::post('pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('section/{id}', 'manageSection')->name('manage.section');
            Route::post('section/{id}', 'manageSectionUpdate')->name('manage.section.update');
        });
    });
    
    // Module Manager
    Route::controller('ModuleController')->name('modules.')->prefix('modules')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('delete');
    });
    
    // Add these routes in the admin group
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('store');
        Route::get('/edit/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('edit');
        Route::post('/update/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('update');
        Route::post('/destroy/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('destroy');
        Route::get('/generate-code', [\App\Http\Controllers\Admin\CouponController::class, 'generateCode'])->name('generate-code');
    });
});
