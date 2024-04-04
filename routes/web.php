<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GincanaController;
use App\Http\Controllers\GincanaSessionController;
use App\Http\Controllers\GincanaSessionGroupController;
use App\Http\Controllers\CurrentActivityController;
use App\Http\Controllers\AuthController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

/* Route::middleware(['admin'])->group(function () {
});
 */

Route::middleware(['auth'])->group(function () {

    Route::get('/test/', function () {
        return view('test');
    })->name('test');

    Route::controller(GincanaController::class)->group(function () {
        Route::get('/api/gincanas/', 'list')->name('api.gincanas.list');
        Route::post('/api/gincanas/', 'store')->name('api.gincanas.store');
        Route::get('/api/points/', 'list_points')->name('api.points.list');
    });

    Route::controller(GincanaSessionController::class)->group(function () {
        Route::get('/api/sessions/', 'list')->name('api.sessions.list');
        Route::post('/api/sessions/', 'store')->name('api.sessions.store');
        Route::post('/api/sessions/start/', 'start')->name('api.sessions.start');
    });

    Route::controller(GincanaSessionGroupController::class)->group(function () {
        Route::get('/api/groups/', 'list')->name('api.groups.list');
        Route::post('/api/groups/', 'store')->name('api.groups.store');
        Route::post('/api/groups/join/', 'join')->name('api.groups.join');
        Route::post('/api/groups/exit/', 'exit')->name('api.groups.exit');
    });

    Route::controller(CurrentActivityController::class)->group(function () {
        Route::get('/api/current-activity/status/', 'status')->name('api.current_activity.status');
        Route::get('/api/current-activity/checkpoint/', 'checkpoint')->name('api.current_activity.checkpoint');
    });

});
