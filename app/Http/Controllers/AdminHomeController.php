<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\MonthlyReport;
use App\Models\ApprovalHistory;
use Illuminate\Support\Facades\Log;


class AdminHomeController extends Controller
{
    /**
     * 管理者ダッシュボードで月報と休暇申請データを表示します。
     *
     * @return \Illuminate\View\View
     *     管理者ホームビューに「承認待ち」の申請データを表示します。
     */
    public function index()
    {
        // 月報データを取得
        $monthlyReports = MonthlyReport::where('status', 'pending')->get();
        $leaveRequests = LeaveRequest::with('user')->where('status', 'pending')->get();

        // 休暇・有給・欠勤申請データを取得
        $leaveRequests = LeaveRequest::where('status', 'pending')->get();

        $applications = $monthlyReports->merge($leaveRequests)->sortByDesc('created_at');

        // ビューにデータを渡す
        return view('admin.home', compact('applications'));
    }

    /**
     * 指定されたIDの休暇申請データを表示します。
     *
     * @param int $id
     *     表示する休暇申請のID。
     *
     * @return \Illuminate\View\View
     *     休暇申請の詳細ページを表示します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDの申請が見つからない場合に例外が発生します。
     */
    public function showLeave($id)
    {
        // 指定されたIDの申請を取得
        $leaveRequest = LeaveRequest::findOrFail($id);

        // 詳細ビューを返す
        return view('admin.showleave', compact('leaveRequest'));
    }

    /**
     * 指定されたIDの月報申請データを表示します。
     *
     * @param int $id
     *     表示する月報申請のID。
     *
     * @return \Illuminate\View\View
     *     月報申請の詳細ページを表示します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDの月報申請が見つからない場合に例外が発生します。
     */
    public function showMonthly($id)
    {
        // 該当する月報申請を取得
        $report = MonthlyReport::with('user')->findOrFail($id);

        // 詳細ページを表示
        return view('admin.showmonthly', compact('report'));
    }

    /**
     * 指定された年と月の承認履歴を取得して表示します。
     *
     * @param \Illuminate\Http\Request $request
     *     リクエストインスタンス。月の指定が含まれることがあります。
     * @param int $year
     *     取得する履歴の対象年。
     * @param int $month
     *     取得する履歴の対象月。
     *
     * @return \Illuminate\View\View
     *     指定された月の承認履歴を表示します。
     */
    public function history(Request $request,$year, $month)
    {
        // デバッグ1: ここでリクエストの内容を確認
        \Log::info('History method called', ['year' => $year, 'month' => $month, 'request' => $request->all()]);
    
        //パラメーターがない場合には現在の年月を設定
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        // リクエストパラメータから送信された月を優先
        if ($request->has('month')) {
            [$year, $month] = explode('-', $request->input('month')); 
        }
        // データ取得処理
        //$monthString = sprintf('%04d-%02d', $year, $month); // YYYY-MM形式に変換
        $histories = ApprovalHistory::whereYear('created_at', $year)
                                    ->whereMonth('created_at', $month)
                                    ->get();

        // デバッグ用
        \Log::info('Histories retrieved', ['histories' => $histories->toArray()]);


        // 日本語化処理
        foreach ($histories as $history) {
            $history->application_type_jp = match ($history->application_type) {
                'App\Models\MonthlyReport' => '月報申請',
                'App\Models\LeaveRequest' => '休暇申請',
                default => '不明な申請',
            };
        }

        return view('admin.history', [
            'histories' => $histories,
            'year' => $year,
            'month' => $month,
            'noDataMessage' => $histories->isEmpty() ? '該当するデータがありません。' : null,
        ]);
    }
}
