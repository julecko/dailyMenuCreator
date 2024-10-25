<?php

use App\Http\Controllers\CalendarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\MenuPageController;
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
Route::post('/calendar', [CalendarController::class, 'createMonth'])
    ->name('calendarMonth');
Route::post('/update', [MenuPageController::class, 'updateFood'])
    ->name('updateFood');
Route::post('/generate', [MenuPageController::class, 'generateMenu'])
    ->name('generateMenu');
