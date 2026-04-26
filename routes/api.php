<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\OsaController;
use App\Http\Controllers\EventController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/admin/login', [AdminLoginController::class, 'adminLogin']);

Route::middleware(['auth:sanctum', 'abilities:osa'])->group(function () {
    Route::post('/osa/register-org', [OsaController::class, 'registerOrganization']);
});


//ORG PAGES
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/org/events', [EventController::class, 'createEvent']);
    Route::get('/org/events', [EventController::class, 'index']);
    Route::get('/org/events/{id}', [EventController::class, 'show']);   
    Route::put('/org/events/{id}', [EventController::class, 'update']);
});

