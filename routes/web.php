<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GincanaController;
use App\Http\Controllers\GincanaSessionController;
use App\Http\Controllers\GincanaSessionGroupController;
use App\Http\Controllers\CurrentActivityController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\LabelController;
use App\Http\Controllers\pointController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLabelController;
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

Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

/* Route::middleware(['admin'])->group(function () {
});
 */

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('index');
    });    

    Route::get('/test', function () {
        return view('test');
    })->name('test');

    Route::get('index', function () {
        return view('index');
    });    

    Route::controller(GincanaController::class)->group(function () {
        Route::get('/api/gincanas/', 'list')->name('api.gincanas.list');
        Route::post('/api/gincanas/', 'store')->name('api.gincanas.store');
        Route::get('/api/points/', 'all_points')->name('api.points.list');
        Route::post('/api/points/', 'list_points');
        Route::post('/api/points/search', 'list_points_search');
        Route::get('/api/gincanas/create/', 'create')->name('api.gincanas.create');
    });

    Route::controller(GincanaSessionController::class)->group(function () {
        Route::get('/api/sessions/', 'list')->name('api.sessions.list');
        Route::post('/api/sessions/', 'store')->name('api.sessions.store');
        Route::get('/api/session/newSession', 'newSession')->name('api.sessions.newSession');
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
        Route::post('/api/current-activity/checkpoint/', 'checkpoint')->name('api.current_activity.checkpoint');
        Route::post('/api/current-activity/exit/', 'exit')->name('api.current_activity.exit');
    });

    //Crea rutas para el controlador de user
    Route::controller(UserController::class)->group(function () {
        Route::get('/user/data', 'userdata');
    });

    Route::controller(UserLabelController::class)->group(function () {
        Route::get('/userlabel/{id}', 'data');
        Route::post('/userlabel/store', 'store');
        Route::post('/userlabel/delete', 'delete');
        Route::post('/userlabel/store_point', 'store_point');
        Route::post('/userlabel/delete_point', 'delete_point');
    });
});

Route::middleware(['admin'])->group(function () {
    Route::controller(LabelController::class)->group(function () {
        Route::get('/admin/label','index')->name('label.index');
        Route::get('/label/getlabel','getlabel')->name('label.getlabel');
        Route::post('/admin/label/list','list')->name('label.list');
        Route::post('/admin/label/store','store')->name('label.store');
        Route::post('/admin/label/delete','delete')->name('label.delete');
        Route::post('/admin/label/show','show')->name('label.show');
        Route::post('/admin/label/update','update')->name('label.update');
    });
    
    Route::controller(pointController::class)->group(function () {
        Route::get('/admin/point/','index')->name('point.index');
        Route::post('/admin/point/list','list')->name('point.list');
        Route::post('/admin/point/update','update')->name('point.update');
        Route::post('/admin/point/show','show')->name('point.show');
        Route::post('/admin/point/store','store')->name('point.store');
        Route::post('/admin/point/delete','delete')->name('point.delete');
    });
    
    Route::controller(UserController::class)->group(function () {
        Route::get('admin/user/','index')->name('user.index');
        Route::post('admin/user/list','list')->name('user.list');
        Route::post('admin/user/store','store')->name('user.store');
        Route::post('admin/user/delete','delete')->name('user.delete');
        Route::post('admin/user/show','show')->name('user.show');
        Route::post('admin/user/update','update')->name('user.update');
    });
});