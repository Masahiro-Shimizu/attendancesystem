<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Models\Attendance;

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
Route::middleware(['auth'])->group(function()
{
    Route::get('/punch-in',[App\Http\Controllers\AttendanceController::class,'punchIn'])->name('punch-in');
    Route::post('/punch-in',[App\Http\Controllers\AttendanceController::class,'punchIn'])->name('punch-in');
    Route::get('/punch-out',[App\Http\Controllers\AttendanceController::class,'punchOut'])->name('punch-out');
    Route::post('/punch-out',[App\Http\Controllers\AttendanceController::class,'punchOut'])->name('punch-out');
});

//まずは一通り形にしてみる
//新規ユーザー登録は管理者権限で実行出来るようにする
//マルチログインの実装
//一覧画面で打刻し、また一覧画面に戻ってくる
//日次勤怠を一覧化
//給与関係まではやらない
//画面は一覧画面、日次勤怠、月報、その他は後で考える
//6/8ミドルウェアについて理解が足りていない

//6/15
//ポップアップ表示のやり方