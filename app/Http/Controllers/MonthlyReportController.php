<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Time; // 勤怠データのモデル
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlyReportController extends Controller
{
    // 承認待ちの月報を表示するページ
    public function index()
    {
        $reports = MonthlyReport::where('status', 'pending')->get();
        return view('admin.monthly_reports.index', compact('reports'));
    }

    // 月報を承認する処理
    public function approve($id)
    {
        $report = MonthlyReport::findOrFail($id);
        $report->status = 'approved';
        $report->save();

        return redirect()->route('monthly_report.approval')->with('success', '月報を承認しました。');
    }

    // 月報を却下する処理
    public function reject($id)
    {
        $report = MonthlyReport::findOrFail($id);
        $report->status = 'rejected';
        $report->save();

        return redirect()->route('monthly_report.approval')->with('success', '月報を却下しました。');
    }
}
