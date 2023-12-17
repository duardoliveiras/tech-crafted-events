<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EventController;

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

Route::get('/api/events/{event}', [EventController::class, 'showJson'])
    ->middleware(['auth']);

Route::put('/api/events', [EventController::class, 'updateJson'])
    ->middleware(['auth']);

Route::get('/api/events', [EventController::class, 'storeJson'])
    ->middleware(['auth']);