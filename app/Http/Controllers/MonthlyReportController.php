<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Models\Time; // 勤怠データのモデル
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlyReportController extends Controller
{
    // 月報申請フォームを表示するメソッド
    public function create()
    {
        return view('monthly_report.create');
    }

    // 月報申請を保存するメソッド
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'month' => 'required|date_format:Y-m',
            // 他の必要なフィールドのバリデーションルール
        ]);

        // フル日付に変換 (月の最初の日を作成)
        $month = $request->input('month') . '-01'; // YYYY-MM-01 の形式に変換


        // 月報データを保存する処理
        MonthlyReport::create([
            'user_id' => auth()->id(),
            'month' => $month,  // 修正後の月
            'status' => 'pending',  // 申請時は保留状態
        ]);

        return response()->json(['message' => '月報を申請しました。']);
        //redirect()->route('monthly_report.create')->with('success', '月報を申請しました');
    }

    // 月報一覧（承認・却下用）
    public function index()
    {
        $reports = MonthlyReport::with('user')->where('status', 'pending')->get();
        return view('admin.home', compact('reports'));
    }

    // 承認処理
    public function approve($id)
    {
        $report = MonthlyReport::findOrFail($id);
        $report->status = 'approved';
        $report->save();

        return redirect()->back()->with('success', '月報が承認されました');
    }

    // 却下処理
    public function reject($id)
    {
        $report = MonthlyReport::findOrFail($id);
        $report->status = 'rejected';
        $report->save();

        return redirect()->back()->with('success', '月報が却下されました');
    }
}