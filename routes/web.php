<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| ここではアプリケーションのWebルートを登録します。このルートは、RouteServiceProvider
| によってロードされ、"web" ミドルウェアグループが含まれています。アプリケーションを
| 構築するための新しいルートを登録してください。
|
*/

// ====================================================
// 公開ルート
// ====================================================

/**
 * アプリケーションのウェルカムページを表示。
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * 管理者ログインフォームを表示するルート。
 */
Route::get('/admin/login', [AdminController::class, 'showAdminLoginForm'])->name('admin.login');

/**
 * 管理者ログイン処理を行うルート。
 */
Route::post('/admin/login', [AdminController::class, 'adminLogin']);

// ====================================================
// 管理者用ルート
// ====================================================

/**
 * 管理者専用ルートグループ。
 *
 * ミドルウェア:
 * - `auth:admin`: 管理者用認証を実行。
 * - `admin`: 管理者権限を確認。
 */
Route::middleware(['auth:admin', 'admin'])->group(function() {
    /**
     * 管理者用ホームページ。
     */
    Route::get('/admin/home', [AdminHomeController::class, 'index'])->name('admin.home');

    /**
     * 月報承認/却下の管理ルート。
     */
    Route::get('/monthly_report/approval', [App\Http\Controllers\MonthlyReportController::class, 'index'])->name('monthly_report.approval');
    Route::post('/monthly_report/approve/{id}', [App\Http\Controllers\MonthlyReportController::class, 'approve'])->name('monthly_report.approve');
    Route::post('/monthly_report/reject/{id}', [App\Http\Controllers\MonthlyReportController::class, 'reject'])->name('monthly_report.reject');

    /**
     * 管理者のユーザー管理ページ。
     */
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/promote/{id}', [AdminController::class, 'promote'])->name('admin.promote');

    /**
     * 休暇申請管理ルート。
     */
    Route::get('/admin/leave_requests', [App\Http\Controllers\LeaveRequestController::class, 'adminIndex'])->name('admin.leave_requests.index');
    Route::get('/admin/leave_requests/{id}', [AdminHomeController::class, 'showLeave'])->name('admin.leave_requests.show');
    Route::post('/admin/leave_requests/approve/{id}', [App\Http\Controllers\LeaveRequestController::class, 'approve'])->name('admin.leave_requests.approve');
    Route::post('/admin/leave_requests/reject/{id}', [App\Http\Controllers\LeaveRequestController::class, 'reject'])->name('admin.leave_requests.reject');

    /**
     * 月報申請詳細表示。
     */
    Route::get('/admin/monthly_report/{id}', [AdminHomeController::class, 'showMonthly'])->name('admin.monthly_report.show');

    /**
     * 承認/却下履歴を表示するルート。
     */
    Route::get('/admin/history/{year?}/{month?}', [AdminHomeController::class, 'history'])
        ->name('admin.history')
        ->where(['year' => '[0-9]+', 'month' => '[0-9]+'])
        //->defaults('year', now()->year)
        ->defaults('month', now()->format('m'));
});

// ====================================================
// 認証ルート
// ====================================================

/**
 * Laravel認証ルートを登録。
 */
Auth::routes();

/**
 * ホームページを表示するルート。
 */
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ====================================================
// ユーザー認証が必要なルート
// ====================================================

Route::middleware(['auth'])->group(function() {
    /**
     * 勤怠データ管理ルート。
     */
    Route::get('/times', [TimesController::class, 'index'])->name('times.index');
    Route::post('/punch-in', [TimesController::class, 'punchIn'])->name('punch-in');
    Route::post('/break-start', [TimesController::class, 'breakStart'])->name('break-start');
    Route::post('/break-end', [TimesController::class, 'breakEnd'])->name('break-end');
    Route::post('/punch-out', [TimesController::class, 'punchOut'])->name('punch-out');

    /**
     * 勤怠詳細ページ。
     */
    Route::get('/times/calendar', [TimesController::class, 'showCalendar'])->name('times.calendar');
    Route::get('/times/get-id-by-date', [TimesController::class, 'getIdByDate'])->name('times.getIdByDate');
    Route::get('/times/detail/{id}', [TimesController::class, 'detail'])->name('times.detail');

    /**
     * 勤怠編集ページ。
     */
    Route::get('/times/edit/{id}', [TimesController::class, 'edit'])->name('times.edit');
    Route::put('/times/{id}', [TimesController::class, 'update'])->name('times.update');

    /**
     * 月報管理ルート。
     */
    Route::get('/monthly_report', [TimesController::class, 'monthlyReport'])->name('times.monthly');
    Route::get('/monthly_report/create', [App\Http\Controllers\MonthlyReportController::class, 'create'])->name('monthly_report.create');
    Route::post('/monthly_report/store', [App\Http\Controllers\MonthlyReportController::class, 'store'])->name('monthly_report.store');

    /**
     * 休暇申請管理ルート。
     */
    Route::get('/leave_requests', [App\Http\Controllers\LeaveRequestController::class, 'index'])->name('leave_requests.index');
    Route::get('/leave_requests/create', [App\Http\Controllers\LeaveRequestController::class, 'create'])->name('leave_requests.create');
    Route::post('/leave_requests', [App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leave_requests.store');

    /**
     * 通知管理ルート。
     */
    Route::post('/notifications/check/{id}', [NotificationController::class, 'check'])->name('notifications.check');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    /**
     * プロフィールページのルート
     */
    Route::get('/profile/{id}',[ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
