<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api.key')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::apiResource('attendees', AttendeeController::class);
    Route::post('/events/register', [AttendeeController::class, 'registraEvento']);
    Route::delete('/events/unregister', [AttendeeController::class, 'rimuoviRegistrazioneDaEvento']);
});
