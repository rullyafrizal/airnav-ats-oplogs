<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OperationalLogController;
use App\Http\Controllers\UsersController;
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

Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::group(['middleware' => 'guest'], function () {
    // Auth
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login')
        ->middleware('guest');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store')
        ->middleware('guest');
});

Route::group(['middleware' => 'auth'], function () {
    // Operational Logs
    Route::resource('operational-logs', OperationalLogController::class)
        ->only(['store', 'index', 'update', 'create', 'edit', 'destroy']);

    Route::get('operational-logs/{operational_log}/action/download', [OperationalLogController::class, 'export'])
        ->name('operational-logs.export');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
});

// Images
Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
