<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DegreeProgramController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrgApplicationController;
use App\Http\Controllers\OsaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\AnnouncementController;

/* AUTH */
Route::post('/admin/login', [AdminLoginController::class, 'adminLogin']);

Route::controller(StudentController::class)->group(function () {
    Route::post('/register-student', 'store');
    Route::post('/student-login', 'login');
});

/* PUBLIC */
Route::get('/degree-programs', [DegreeProgramController::class, 'index']);
Route::get('/organizations', [OrganizationController::class, 'index']);
Route::get('/announcements', [AnnouncementController::class, 'index']);

/* OSA */
Route::middleware(['auth:sanctum', 'abilities:osa'])->group(function () {
    Route::post('/osa/organizations', [OsaController::class, 'store']);
    Route::get('/osa/organizations', [OsaController::class, 'index']);
    Route::put('/osa/organizations/{id}', [OsaController::class, 'update']);
    Route::delete('/osa/organizations/{id}', [OsaController::class, 'destroy']);
    Route::get('/osa/students', [OsaController::class, 'studentDirectory']);
    Route::get('/osa/degree-programs', [DegreeProgramController::class, 'getProgramsWithColleges']);
    Route::post('/organizations/reactivate/{id}', [OrganizationController::class, 'reactivate']);

    // osa announcements
    Route::post('/osa/announcements', [AnnouncementController::class, 'store']);
    Route::get('/osa/announcements/my', [AnnouncementController::class, 'myAnnouncements']);
    Route::put('/osa/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/osa/announcements/{id}', [AnnouncementController::class, 'destroy']);
});

/* ORGANIZATION */
Route::middleware(['auth:sanctum', 'abilities:org'])->group(function () {
    // Events
    Route::post('/org/events', [EventController::class, 'store']);
    Route::get('/org/events', [EventController::class, 'index']);
    Route::get('/org/events/{id}', [EventController::class, 'show']);
    Route::put('/org/events/{id}', [EventController::class, 'update']);
    Route::delete('/org/events/{id}', [EventController::class, 'destroy']);

    // Review Applications
    Route::get('/org/applications', [OrgApplicationController::class, 'index']);
    Route::put('/org/applications/{id}', [OrgApplicationController::class, 'update']);

    // Manage Members
    Route::get('/org/members', [OrgApplicationController::class, 'show']);
    Route::delete('/org/members/{id}', [OrgApplicationController::class, 'destroy']);

    // Announcements
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::get('/announcements/my', [AnnouncementController::class, 'myAnnouncements']);
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);

    // Org Profile
    Route::get('/org/profile', [OrganizationController::class, 'show']);
    Route::put('/org/status', [OrganizationController::class, 'updateStatus']);
});

/* STUDENT (AUTHENTICATED) */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/memberships', [MembershipController::class, 'index']);
    Route::get('/events', [StudentController::class, 'index']);

});

