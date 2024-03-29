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

Route::get('/singup', [AuthController::class, 'singup'])
    ->middleware('guest')->name('singup');
Route::post('/singup', [AuthController::class, 'singup_submit'])
    ->middleware('guest')->name('singup_submit');
Route::get('/login', [AuthController::class, 'login'])
    ->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login_submit'])
    ->middleware('guest')->name('login_submit');
Route::get('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::get('/library', [LibraryController::class, 'index'])
    ->middleware('auth')->name('library');
Route::get('/library/{lid}', [LibraryController::class, 'video'])
    ->middleware('auth')->name('library_video');

Route::post('/api/video_data', [LibraryController::class, 'updateVideoData'])
    ->middleware('auth')->name('video_data');
Route::delete('/api/video_data', [LibraryController::class, 'deleteVideo'])
    ->middleware('auth')->name('video_delete');
Route::post('/api/upload_blob', [LibraryController::class, 'uploadBlobVideo'])
    ->middleware('auth')->name('upload_blob');

Route::post('/api/view/{lid}', [LibraryController::class, 'registerVideoView'])
    ->name('register_view');
Route::get('/stream/{lid}', [StreamController::class, 'video'])
    ->name('stream_video');
Route::get('/thumb/{lid}', [StreamController::class, 'thumb'])
    ->name('stream_thumb');

Route::get('/{lid}', [PublicController::class, 'video'])
    ->name('public_video');
