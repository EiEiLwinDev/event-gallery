<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;   

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/upcoming-events', [EventController::class, 'index']);
    Route::get('/events-photos', [EventController::class, 'eventsWithPhotos']);
    Route::post('/events', [EventController::class, 'store']);
    Route::patch('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::get('/photos', [PhotoController::class, 'index']);
    Route::post('/photos', [PhotoController::class, 'store']);
    Route::patch('/photos/{id}/approve', [PhotoController::class, 'approve']);
    Route::patch('/photos/{id}/reject', [PhotoController::class, 'reject']);
    Route::delete('/photos/{id}', [PhotoController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/events', [EventController::class, 'index']);
});
 
