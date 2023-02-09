<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\StreamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PublicController::class, 'index'])->name('index');

Route::post('/upload_blob', [LibraryController::class, 'uploadBlobVideo'])
    ->name('upload_blob');

Route::get('/singup', [AuthController::class, 'singup'])
    ->middleware('guest')->name('singup');
Route::post('/singup', [AuthController::class, 'singup_submit'])
    ->middleware('guest')->name('singup_submit');
Route::get('/login', [AuthController::class, 'login'])
    ->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login_submit'])
    ->middleware('guest')->name('login_submit');

Route::get('/thumb/{lid}', [StreamController::class, 'thumb'])
    ->name('stream_thumb');

Route::get('/{lid}', [PublicController::class, 'video'])
    ->name('public_video');
