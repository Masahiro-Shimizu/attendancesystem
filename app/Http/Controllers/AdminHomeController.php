<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\MonthlyReport;

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
}
