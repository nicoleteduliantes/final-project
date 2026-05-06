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


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//ADMIN LOGIN
Route::post('/admin/login', [AdminLoginController::class, 'adminLogin']);

//OSA ROUTING
Route::middleware(['auth:sanctum', 'abilities:osa'])->group(function () {
    Route::post('/osa/organizations', [OsaController::class, 'store']);
    Route::get('/osa/organizations', [OsaController::class, 'index']);
    Route::put('/osa/organizations/{id}', [OsaController::class, 'update']);
    Route::delete('/osa/organizations/{id}', [OsaController::class, 'destroy']);
});

//ORG ROUTING
Route::middleware('auth:sanctum', 'abilities:org')->group(function () {
    Route::post('/org/events', [EventController::class, 'store']);
    Route::get('/org/events', [EventController::class, 'index']);
    Route::get('/org/events/{id}', [EventController::class, 'show']);
    Route::put('/org/events/{id}', [EventController::class, 'update']);
    Route::delete('/org/events/{id}', [EventController::class, 'destroy']);

    Route::get('/org/applications', [OrgApplicationController::class, 'index']);
    Route::put('/org/applications/{id}', [OrgApplicationController::class, 'update']);
    Route::get('/org/members', [OrgApplicationController::class, 'show']);
    Route::delete('/org/members/{id}', [OrgApplicationController::class, 'destroy']);

    });

/*
Route::middleware(['auth:sanctum', 'abilities:student'])->group(function () {
    For future pani
});
*/

//STUDENT ROUTING

Route::controller(StudentController::class)->group(function() {
        Route::post('/register-student', 'store');
        Route::post('/student-login', 'login');
    });


Route::get('/degree-programs', [DegreeProgramController::class, 'index']); 

Route::get('/organizations', [OrganizationController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'checkMembership']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/applications', [ApplicationController::class, 'store']);

});

Route::middleware('auth:sanctum')->get('/memberships', [MembershipController::class, 'index']);


Route::get('student/events', [StudentController::class, 'index']);

