<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\DegreeProgramController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OsaController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ApplicationController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/admin/login', [AdminLoginController::class, 'adminLogin']);

Route::middleware(['auth:sanctum', 'abilities:osa'])->group(function () {
    Route::controller(OsaController::class)->group(function() {
        Route::post('/osa/register-org', 'registerOrganization');
        Route::get('/osa', 'test');
    });
});

//ORG PAGES
Route::middleware('auth:sanctum', 'abilities:org')->group(function () {
    Route::post('/org/events', [EventController::class, 'store']);
    Route::get('/org/events', [EventController::class, 'index']);
    Route::get('/org/events/{id}', [EventController::class, 'show']);
    Route::put('/org/events/{id}', [EventController::class, 'update']);
    Route::delete('/org/events/{id}', [EventController::class, 'destroy']);
});

/*
Route::middleware(['auth:sanctum', 'abilities:student'])->group(function () {
    For future pani
});
*/

Route::controller(StudentController::class)->group(function() {
        Route::post('/register-student', 'store');
        Route::post('/student-login', 'login');
    });


Route::get('/degree-programs', [DegreeProgramController::class, 'getAll']); 

Route::get('/organizations', [OrganizationController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'checkMembership']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/applications', [ApplicationController::class, 'store']);
    
});


