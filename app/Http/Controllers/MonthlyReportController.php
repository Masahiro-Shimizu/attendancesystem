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
        // ログインしているユーザーのIDを取得
        Auth::user()->id;

        // ロケールを日本語に設定
        Carbon::setLocale('ja');

        // 現在の年月を取得
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        //$times = Time::where('user_id', $id)->get();  // 全ての勤怠データを取得

        // 現在ログインしているユーザーの指定された月の勤怠データを取得
        $times = Time::where('user_id', $userId)
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
