<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Models\Time; // 勤怠データのモデル
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlyReportController extends Controller
{
    // 月報申請フォームを表示するメソッド
    public function create()
    {
        // ログインユーザーの最新の月報申請を取得
        $latestApplication = MonthlyReport::where('user_id', auth()->id())
         ->orderBy('created_at', 'desc')
         ->first();

        return view('monthly_report.create', compact('latestApplication'));
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

        // ログインユーザーの同じ月の最新の申請を取得
        $existingApplication = MonthlyReport::where('user_id', auth()->id())
            ->where('month', $month)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingApplication && $existingApplication->status == 'rejected') {
            // 差し戻しのコメントと情報をリセット
            $existingApplication->reject_comment = null;
            $existingApplication->rejected_by = null;  // 管理者名のフィールドもリセット
            $existingApplication->status = 'pending';
            $existingApplication->save();
        } else {
            // 新規作成
            MonthlyReport::create([
                'user_id' => auth()->id(),
                'month' => $month,
                'status' => 'pending',
            ]);
        }    

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
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_comment' => 'nullable|string|max:500',
        ]);

        $monthlyReport = MonthlyReport::findOrFail($id);
        $monthlyReport->status = 'rejected';
        $monthlyReport->reject_comment = $request->input('reject_comment');
        $monthlyReport->rejected_by = auth()->user()->name;
        $monthlyReport->save();

        // 通知を作成
        Notification::create([
            'user_id' => $monthlyReport->user_id,
            'type' => 'monthly_report_rejected', // 適切なタイプを指定
            'message' => "月報申請が却下されました。理由: " . $request->input('reject_comment'),
            'is_checked' => false,
        ]);

        return redirect()->back()->with('success', '申請を差し戻しました');
    }
}