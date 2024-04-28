<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/time_lists', [App\Http\Controllers\TimeListController::class, 'index'])->name('time_lists.index');
Route::get('/time_lists/create', [App\Http\Controllers\TimeListController::class, 'create'])->name('time_lists.create');
Route::post('/time_lists', [App\Http\Controllers\TimeListController::class, 'store'])->name('time_lists.store');
