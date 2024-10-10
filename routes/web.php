<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimesController;
use App\Models\Times;

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

Route::middleware(['admin'])->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 認証が必要なルートをグループ化
Route::middleware(['auth'])->group(function() {
    //勤怠管理関連のルート
    Route::get('/times', [App\Http\Controllers\TimesController::class, 'index'])->name('times.index');
    
    //出勤と退勤のPOSTリクエストのみ
    //Route::get('/punch-in', [App\Http\Controllers\TimesController::class, 'punchIn'])->name('punch-in');
    Route::post('/punch-in', [App\Http\Controllers\TimesController::class, 'punchIn'])->name('punch-in');
    Route::post('/break-start', [TimesController::class, 'breakStart'])->name('break-start');
    Route::post('/break-end', [TimesController::class, 'breakEnd'])->name('break-end');
    //Route::get('/punch-out', [App\Http\Controllers\TimesController::class, 'punchOut'])->name('punch-out');
    Route::post('/punch-out', [App\Http\Controllers\TimesController::class, 'punchOut'])->name('punch-out');
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
   
    //詳細画面
    Route::get('/times/{id}',[App\Http\Controllers\TimesController::class, 'detail'])->name('times.detail');
    
    //編集画面
    Route::get('/times/edit/{id}',[App\Http\Controllers\TimesController::class, 'edit'])->name('times.edit');
    Route::put('/times/{id}',[App\Http\Controllers\TimesController::class, 'update'])->name('times.update');

    //月報
    Route::get('/monthly_report', [App\Http\Controllers\TimesController::class, 'monthlyReport'])->name('times.monthly');
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
//ポップアップ表示のやり方　←ポップアップは非同期に合わないのではないか？という問題があるため、行わない

//7/25
//ポップアップ表示ではなく、枠の中に文言で表示にする




