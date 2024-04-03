<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LabelController;
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