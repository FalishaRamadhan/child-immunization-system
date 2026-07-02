<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\ImmunizationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\GrowthController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordResetController;

/*
PUBLIC ROUTES
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Password reset
Route::get('/forgot-password',        [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password',       [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',        [PasswordResetController::class, 'resetPassword'])->name('password.update');

/*
AUTHENTICATED ROUTES
*/

Route::middleware('auth')->group(function () {

    /*
    DASHBOARD
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN + HEALTHCARE WORKER ONLY
    | Can register children, edit records, record vaccines, schedule appointments,
    | record growth, and dispatch reminders.
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,healthcare_worker')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CHILDREN MANAGEMENT
        |--------------------------------------------------------------------------
        | IMPORTANT: /children/register must stay before /children/{child}
        */

        Route::get('/children', [ChildController::class, 'index'])
            ->name('children.index');

        Route::get('/children/register', [ChildController::class, 'create'])
            ->name('children.create');

        Route::post('/children', [ChildController::class, 'store'])
            ->name('children.store');

        Route::get('/children/{child}/edit', [ChildController::class, 'edit'])
            ->name('children.edit');

        Route::put('/children/{child}', [ChildController::class, 'update'])
            ->name('children.update');

        /*
        |--------------------------------------------------------------------------
        | IMMUNIZATION RECORDING
        |--------------------------------------------------------------------------
        */

        Route::get('/children/{child}/immunizations/create', [ImmunizationController::class, 'create'])
            ->name('immunizations.create');

        Route::post('/children/{child}/immunizations', [ImmunizationController::class, 'store'])
            ->name('immunizations.store');

        /*
        |--------------------------------------------------------------------------
        | APPOINTMENTS MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/children/{child}/appointments/create', [AppointmentController::class, 'create'])
            ->name('appointments.create');

        Route::post('/children/{child}/appointments', [AppointmentController::class, 'store'])
            ->name('appointments.store');

        Route::patch('/appointments/{appointment}/attend', [AppointmentController::class, 'attend'])
            ->name('appointments.attend');

        Route::patch('/appointments/{appointment}/miss', [AppointmentController::class, 'miss'])
            ->name('appointments.miss');

        /*
        |--------------------------------------------------------------------------
        | GROWTH RECORDING
        |--------------------------------------------------------------------------
        */

        Route::get('/children/{child}/growth/create', [GrowthController::class, 'create'])
            ->name('growth.create');

        Route::post('/children/{child}/growth', [GrowthController::class, 'store'])
            ->name('growth.store');

        /*
        |--------------------------------------------------------------------------
        | REMINDER DISPATCH
        | Only admin and healthcare worker can dispatch SMS reminders.
        |--------------------------------------------------------------------------
        */

        Route::post('/reminders/dispatch', [ReminderController::class, 'dispatch'])
            ->name('reminders.dispatch');
    });

    /*
    |--------------------------------------------------------------------------
    | SHARED VIEW ACCESS
    | Admin + healthcare worker can view all.
    | Caregiver can view their own child data.
    | Caregiver filtering must be handled inside the controllers.
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,healthcare_worker,caregiver')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | REMINDERS VIEW
        |--------------------------------------------------------------------------
        */

        Route::get('/reminders', [ReminderController::class, 'index'])
            ->name('reminders.index');

        /*
        |--------------------------------------------------------------------------
        | CHILD VIEW ROUTES
        | These must stay after /children/register
        |--------------------------------------------------------------------------
        */

        Route::get('/children/{child}/immunizations', [ImmunizationController::class, 'history'])
            ->name('immunizations.history');

        Route::get('/children/{child}/growth', [GrowthController::class, 'chart'])
            ->name('growth.chart');

        Route::get('/children/{child}', [ChildController::class, 'show'])
            ->name('children.show');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | REPORTS
        |--------------------------------------------------------------------------
        */

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        /*
        |--------------------------------------------------------------------------
        | USER MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->name('users.deactivate');

        Route::patch('/users/{user}/reactivate', [UserController::class, 'reactivate'])
            ->name('users.reactivate');
    });

});