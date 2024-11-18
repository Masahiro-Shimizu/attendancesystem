<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminController;
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

// ログイン前にアクセスできるルート
Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'adminLogin']);


Route::middleware(['auth:admin', 'admin'])->group(function() {
    //一覧画面
    Route::get('/admin/home', [App\Http\Controllers\AdminHomeController::class, 'index'])->name('admin.home');
    Route::get('/monthly_report/approval', [App\Http\Controllers\MonthlyReportController::class, 'index'])->name('monthly_report.approval');
    Route::post('/monthly_report/approve/{id}', [App\Http\Controllers\MonthlyReportController::class, 'approve'])->name('monthly_report.approve');
    Route::post('/monthly_report/reject/{id}', [App\Http\Controllers\MonthlyReportController::class, 'reject'])->name('monthly_report.reject');
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/promote/{id}', [App\Http\Controllers\AdminController::class, 'promote'])->name('admin.promote');

    // 申請一覧表示
    Route::get('/admin/leave_requests', [App\Http\Controllers\LeaveRequestController::class, 'adminIndex'])->name('admin.leave_requests.index');

    // 申請詳細を表示するルート
    Route::get('/admin/leave_requests/{id}', [App\Http\Controllers\AdminHomeController::class, 'showLeave'])->name('admin.leave_requests.show');
    // 月報詳細ページへのルート
    Route::get('/admin/monthly_report/{id}', [App\Http\Controllers\AdminHomeController::class, 'showMonthly'])->name('admin.monthly_report.show');

    // 申請承認
    Route::post('/admin/leave_requests/approve/{id}', [App\Http\Controllers\LeaveRequestController::class, 'approve'])->name('admin.leave_requests.approve');
    
    // 申請差し戻し
    Route::post('/admin/leave_requests/reject/{id}', [App\Http\Controllers\LeaveRequestController::class, 'reject'])->name('admin.leave_requests.reject');

    //履歴
    Route::get('/admin/history/{year?}/{month?}', [App\Http\Controllers\AdminHomeController::class, 'history'])
    ->name('admin.history')
    ->where(['year' => '[0-9]+', 'month' => '[0-9]+'])
    ->defaults('year', now()->year)
    ->defaults('month', now()->format('m'));

});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 認証が必要なルートをグループ化
Route::middleware(['auth'])->group(function() {
    //ホームのルート
    Route::get('/times', [App\Http\Controllers\TimesController::class, 'index'])->name('times.index');
    
    //出勤と退勤のPOSTリクエストのみ
    Route::post('/punch-in', [App\Http\Controllers\TimesController::class, 'punchIn'])->name('punch-in');
    Route::post('/break-start', [App\Http\Controllers\TimesController::class, 'breakStart'])->name('break-start');
    Route::post('/break-end', [App\Http\Controllers\TimesController::class, 'breakEnd'])->name('break-end');
    Route::post('/punch-out', [App\Http\Controllers\TimesController::class, 'punchOut'])->name('punch-out');
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
   
    //詳細画面
    Route::get('/times/calendar', [App\Http\Controllers\TimesController::class, 'showCalendar'])->name('times.calendar');
    Route::get('/times/get-id-by-date', [App\Http\Controllers\TimesController::class, 'getIdByDate'])->name('times.getIdByDate');
    Route::get('/times/detail/{id}', [App\Http\Controllers\TimesController::class, 'detail'])->name('times.detail');

    //編集画面
    Route::get('/times/edit/{id}',[App\Http\Controllers\TimesController::class, 'edit'])->name('times.edit');
    Route::put('/times/{id}',[App\Http\Controllers\TimesController::class, 'update'])->name('times.update');

    //月報
    Route::get('/monthly_report', [App\Http\Controllers\TimesController::class, 'monthlyReport'])->name('times.monthly');
    Route::get('/monthly_report/create', [App\Http\Controllers\MonthlyReportController::class, 'create'])->name('monthly_report.create');
    Route::post('/monthly_report/store', [App\Http\Controllers\MonthlyReportController::class, 'store'])->name('monthly_report.store');

    //休暇申請
    Route::get('/leave_requests', [App\Http\Controllers\LeaveRequestController::class, 'index'])->name('leave_requests.index');
    Route::get('/leave_requests/create', [App\Http\Controllers\LeaveRequestController::class, 'create'])->name('leave_requests.create');
    Route::post('/leave_requests', [App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave_requests.store');
    Route::post('/leave_requests/approve/{id}', [App\Http\Controllers\LeaveRequestController::class, 'approve'])->name('leave_requests.approve');
    Route::post('/leave_requests/reject/{id}', [App\Http\Controllers\LeaveRequestController::class, 'reject'])->name('leave_requests.reject');

    //お知らせ確認
    Route::post('/notifications/check/{id}', [NotificationController::class, 'check'])->name('notifications.check');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

