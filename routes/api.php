<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\OsaController;
use App\Http\Controllers\StudentController;


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


Route::get('/degree-programs', [StudentController::class, 'index']); //temporaryy only since wala pa nag make ug degrpog controller

Route::post('/register-student', [StudentController::class, 'registerStudent']);

Route::post('/student-login', [StudentController::class, 'login']);