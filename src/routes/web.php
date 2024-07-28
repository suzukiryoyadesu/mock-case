<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/startwork', [AttendanceController::class, 'startWork']);
    Route::post('/endwork', [AttendanceController::class, 'endWork']);
    Route::post('/startrest', [AttendanceController::class, 'startRest']);
    Route::post('/endrest', [AttendanceController::class, 'endRest']);
    Route::get('/attendance', [AttendanceController::class, 'atteRecord']);
    Route::post('/attendance', [AttendanceController::class, 'atteRecord']);
    Route::get('/attendance/user', [AttendanceController::class, 'userRecord']);
    Route::get('/attendance/month', [AttendanceController::class, 'monthRecord']);
    Route::post('/attendance/month', [AttendanceController::class, 'monthRecord']);
});
