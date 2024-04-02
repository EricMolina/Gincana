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
    Route::get('/label','index')->name('label.index');
    Route::get('/label/create','create')->name('label.create');
    Route::post('/label/list','list')->name('label.list');
});