<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn() => redirect()->route('loginPage'))
    ->name('landing');
Route::get('/login', [AuthController::class, 'createLoginForm'])
    ->name('loginPage');
Route::post('/login', [AuthController::class, 'login'])
    ->name('loginAuth');
Route::get('/menu', [MenuPageController::class, 'create'])
    ->name('menuPage');
Route::get('/edit', fn()=> "Edit page")
    ->name('editPage');
Route::get('/about', fn()=> "About page")
    ->name('aboutPage');
