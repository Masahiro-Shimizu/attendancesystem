<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\MonthlyReport;
use App\Models\ApprovalHistory;
use Illuminate\Support\Facades\Log;


class AdminHomeController extends Controller
{
    // 管理者ダッシュボードで月報と休暇・有給・欠勤申請のデータを表示
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

    public function showLeave($id)
    {
        // 指定されたIDの申請を取得
        $leaveRequest = LeaveRequest::findOrFail($id);

        // 詳細ビューを返す
        return view('admin.showleave', compact('leaveRequest'));
    }

    public function showMonthly($id)
    {
        // 該当する月報申請を取得
        $report = MonthlyReport::with('user')->findOrFail($id);

        // 詳細ページを表示
        return view('admin.showmonthly', compact('report'));
    }

    public function history(Request $request,$year, $month)
    {
        // リクエストパラメータから送信された月を優先
        if ($request->has('month')) {
            [$year, $month] = explode('-', $request->input('month')); 

        // データ取得処理
        $monthString = sprintf('%04d-%02d', $year, $month); // YYYY-MM形式に変換
        $histories = ApprovalHistory::where('created_at', 'like', "$monthString%")->get();

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
}