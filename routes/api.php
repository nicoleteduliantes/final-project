<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\OsaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DegreeProgramController;


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

/*
Route::middleware(['auth:sanctum', 'abilities:student'])->group(function () {

});
*/

Route::controller(StudentController::class)->group(function() {
        Route::post('/register-student', 'store');
        Route::post('/student-login', 'login');
    });


Route::get('/degree-programs', [DegreeProgramController::class, 'getAll']); //temporary only since wala pa nag make ug degrpog controller



