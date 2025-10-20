<?php

use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::namespace('Auth')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->name('logout');
        });
    });

// Protected Organization Routes
Route::middleware('organization')->group(function () {
    
    // Dashboard
    Route::controller('DashboardController')->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'updateProfile')->name('profile.update');
    });
    
    // Employee Management
    Route::controller('EmployeeController')->prefix('employees')->group(function () {
        Route::get('/', 'index')->name('employees.index');
        Route::get('/create', 'create')->name('employees.create');
        Route::post('/', 'store')->name('employees.store');
        Route::get('/{id}', 'show')->name('employees.show');
        Route::get('/{id}/edit', 'edit')->name('employees.edit');
        Route::put('/{id}', 'update')->name('employees.update');
        Route::delete('/{id}', 'destroy')->name('employees.destroy');
        
        // Employee enrollment management
        Route::delete('/{employee}/enrollment/{enrollment}', 'destroyEnrollment')->name('employees.enrollment.destroy');
    });
    
});