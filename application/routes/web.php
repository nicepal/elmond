<?php

use App\Lib\Router;
use Illuminate\Support\Facades\Route;

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->group(function () {
    Route::get('/', 'supportTicket')->name('ticket');
    Route::get('/new', 'openSupportTicket')->name('ticket.open');
    Route::post('/create', 'storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'replyTicket')->name('ticket.reply');
    Route::post('/close/{ticket}', 'closeTicket')->name('ticket.close');
    Route::get('/download/{ticket}', 'ticketDownload')->name('ticket.download');
});


Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {
    // -----------------Contact route-----------------
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');

    // -----------------Language route-----------------
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    // -----------------Cookie route-----------------
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    // -----------------Policy route-----------------
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    // -----------------PlaceHolder Image route-----------------
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    // -----------------Blog route-----------------
    Route::get('blog', 'blogPost')->name('blog');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');
    Route::get('blog-search', 'blogSearch')->name('blog.search');

    // -----------------About route-----------------
    Route::get('about', 'about')->name('about');
    
    // -----------------categories route-----------------
    Route::get('categories', 'categories')->name('categories');

    // -----------------Course route-----------------
    Route::get('/category/course', 'categoryCourse')->name('category.course');
    Route::get('course/{id?}', 'course')->name('course');
    Route::get('course/{slug}/{id}', 'courseDetails')->name('course.details');
    Route::post('course/preview', 'coursePreview')->name('lesson.preview');
    Route::get('lesson/document/download/{id}', 'lessonDocumentDownload')->name('lesson.document.download');
    Route::post('course/search', 'courseSearch')->name('course.search');


    // subscriber
    Route::post('subscribe','subscribe')->name('subscribe');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');

  
});

// Add these routes in the appropriate user group
Route::middleware('auth')->group(function () {
    // Coupon routes
    Route::post('coupon/apply', [\App\Http\Controllers\User\CouponController::class, 'applyCoupon'])->name('user.coupon.apply');
    Route::get('coupon/remove', [\App\Http\Controllers\User\CouponController::class, 'removeCoupon'])->name('user.coupon.remove');
});

// For guest users (non-authenticated)
Route::post('coupon/apply', [\App\Http\Controllers\User\CouponController::class, 'applyCoupon'])->name('user.coupon.apply');
Route::get('coupon/remove', [\App\Http\Controllers\User\CouponController::class, 'removeCoupon'])->name('user.coupon.remove');


