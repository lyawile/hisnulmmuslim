<?php

use App\Http\Controllers\HadeethController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('/users', UsersController::class);
Route::post('/register', [UsersController::class, 'register'])->name('user.register');
Route::post('/login', [UsersController::class, 'register'])->name('user.login');
Route::get('/hadeeth/get-audio-files', [HadeethController::class, 'getAudioFiles']);
Route::resource('/hadeeth', HadeethController::class);
Route::post('/hadeeth/upload', [HadeethController::class, 'upload']);
