<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\pointController;
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

Route::controller(LabelController::class)->group(function () {
    Route::get('/admin/label','index')->name('label.index');
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