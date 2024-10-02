<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Time; // 勤怠データのモデル
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        // 現在の年月を取得
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // 現在ログインしているユーザーの指定された月の勤怠データを取得
        $times = Time::where('user_id', Auth::id())
                    ->whereYear('punchIn', $year)
                    ->whereMonth('punchIn', $month)
                    ->orderBy('punchIn', 'asc')
                    ->get();

        // もしデータが取得できなかった場合は空のコレクションを返す
        $times = $times ?? collect(); 
        // 月の日数を取得
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // データをビューに渡す
        return view('reports.monthly', compact('times', 'year', 'month', 'daysInMonth'));
    }
}
