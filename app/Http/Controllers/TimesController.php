<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Time;
use App\Models\MonthlyReport;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TimesController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->startOfDay();

        $items = Time::where('user_id', Auth::id())
            ->orderBy('punchIn', 'desc')
            ->get()  // クエリビルダーではなくコレクションを返す
            ->groupBy(function($date) {
                return Carbon::parse($date->punchIn)->format('Y-m-d');
            })
            ->take(3);

        $rejectedMonthlyReports = MonthlyReport::where('user_id', auth()->id())
                                 ->where('status', 'rejected')
                                 ->get();

        $rejectedLeaveRequests = LeaveRequest::where('user_id', auth()->id())
                               ->where('status', 'rejected')
                               ->get();

        $notifications = Notification::where('user_id', auth()->id())
                       ->where('is_checked', false)
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('home', compact('items', 'rejectedMonthlyReports', 'rejectedLeaveRequests', 'notifications'));
    }

    public function punchIn()
    {
        $currentTime = Carbon::now('Asia/Tokyo');
    \Log::info('PunchIn Time:', ['time' => $currentTime]);

    try {
        $timeEntry = Time::create([
            'user_id' => Auth::id(),
            'punchIn' => $currentTime,
        ]);

        \Log::info('Time Entry Created:', ['entry' => $timeEntry->toArray()]);
    } catch (\Exception $e) {
        \Log::error('Failed to create Time Entry:', ['error' => $e->getMessage()]);
    }

        //$currentTime = Carbon::now('Asia/Tokyo');
        //\Log::info('PunchIn Time:', ['time' => $currentTime]);

        //Time::create([
            //'user_id' => Auth::id(),
            //'punchIn' => Carbon::now('Asia/Tokyo'),
        //]);

        return response()->json(['message' => '出勤しました']);
    }

    public function punchOut()
    {
        \Log::info('PunchOut method called for user:', ['user_id' => Auth::id()]);

        $attendance = Time::where('user_id', Auth::id())
                            ->whereNull('punchOut')
                            ->orderBy('punchIn', 'desc')
                            ->first();

                            if ($attendance) {
                                $currentTime = Carbon::now('Asia/Tokyo');
                                \Log::info('Updating PunchOut time for user:', ['user_id' => Auth::id(), 'time' => $currentTime]);
                        
                                try {
                                    $attendance->update([
                                        'punchOut' => $currentTime,
                                    ]);
                        
                                    \Log::info('Updated Attendance Record:', ['attendance' => $attendance->toArray()]);
                                } catch (\Exception $e) {
                                    \Log::error('Failed to update PunchOut:', ['error' => $e->getMessage()]);
                                }
        
                            return response()->json(['message' => '退勤しました']);
        }

        return response()->json(['message' => '退勤処理に失敗しました'], 400);
    }

    // 休憩開始
    public function breakStart()
    {
        // ログ: メソッドが呼ばれた時点
        Log::info('休憩開始: User ID: ', ['user_id' => Auth::id()]);

        $times = Time::where('user_id', Auth::id())
            ->whereNull('punchOut') // 退勤していない
            ->first();

        if ($times) {
            // 現在時刻をログに出力
            Log::info('現在時刻（休憩開始前）: ', ['now' => Carbon::now()]);
            // 休憩開始時間を一時的に保存
            //$times->update(['break_start' => Carbon::now()]);
            //$times->save();
            // 休憩開始時間を一時的に保存
            $times->break_start = Carbon::now();

            // 保存前のデータをログに出力
            Log::info('保存前の勤怠データ: ', ['times' => $times]);

            $times->save();

            //Log::info('休憩開始時刻を記録: ', ['break_start' => $times->break_start]);
            // 保存後のデータをログに出力
            Log::info('保存後の勤怠データ: ', ['times' => $times]);
            return response()->json(['message' => '休憩を開始しました']);
        }

        Log::error('休憩開始処理に失敗しました: User ID', ['user_id' => Auth::id()]);
        return response()->json(['message' => '休憩開始処理に失敗しました'], 400);
    }

    // 休憩終了
    public function breakEnd()
    {
        $times = Time::where('user_id', Auth::id())
        ->whereNull('punchOut') // 退勤していない
        ->first();

    if ($times && $times->break_start) {
        // 現在時刻（休憩終了時刻）をログに出力
        $breakEndTime = Carbon::now();
        Log::info('現在時刻（休憩終了）: ', ['break_end' => $breakEndTime]);

        // break_start を Carbon インスタンスに変換してログに出力
        $breakStartTime = Carbon::parse($times->break_start);
        Log::info('休憩開始時刻: ', ['break_start' => $breakStartTime]);

        // 休憩開始時刻と終了時刻の差を計算してログに出力
        $breakDuration = $breakStartTime->diffInMinutes($breakEndTime);
        Log::info('休憩時間（分）: ', ['break_duration' => $breakDuration]);

        // 既存の休憩時間に追加
        $updatedBreakTime = $times->break_time + $breakDuration;

        // 新しい休憩時間をログに出力
        Log::info('更新された休憩時間: ', ['updated_break_time' => $updatedBreakTime]);

        // 休憩時間を加算
        $times->break_time = $updatedBreakTime;
        $times->break_start = null;
        $times->save();

        // 保存後のデータをログに出力
        Log::info('休憩終了後の勤怠データ: ', ['times' => $times]);

        return response()->json(['message' => '休憩を終了しました']);
    }

        return response()->json(['message' => '休憩終了処理に失敗しました'], 400);
    }
    
    public function detail($id)
    {
        $times = Time::find($id);

        if (!$times) {
            return redirect()->back()->with('error', '該当の勤怠データが見つかりません。');
        }

        // ビューに勤怠データを渡す
        return view('detail', compact('times'));
    }

    public function edit($id)
    {
        //特定の打刻データを取得
        $time = Time::findOrFail($id);

        // punchIn, punchOut を Carbon に変換
        $time->punchIn = Carbon::parse($time->punchIn);
        if ($time->punchOut) {
        $time->punchOut = Carbon::parse($time->punchOut);
        }

        //詳細ページのビューにデータを渡す
        return view('edit',compact('time'));
    }

    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'punchIn' => 'required|date',
            'punchOut' => 'nullable|date',
            'comments' => 'nullable|string|max:255',
            'method' => 'required|string',
            'break_time' => 'nullable|integer|min:0',
        ]);

        //更新する打刻データを取得
        $time = Time::findOrFail($id);

        // 休憩時間が指定されている場合、更新（分単位で処理）
        if ($request->filled('break_time')) {
        // `break_time` が数値（分）で送信されてきた場合
        $breakMinutes = $request->input('break_time');
        // 休憩時間を分単位で保存する (例: 60分なら "01:00:00" として保存)
        $time->break_time = Carbon::createFromTime(0, $breakMinutes)->format('H:i:s');
            } else {
            // 休憩時間が入力されていない場合は null として保存
            $time->break_time = null;
        }

        //データを更新
        $time->update([
            'punchIn' => $request->input('punchIn'),
            'punchOut' => $request->input('punchOut'),
            'method' => $request->input('method'),
            'comments' => $request->input('comments'),
        ]);

        // リクエストからコメントを含む他のフィールドも更新
        $time->update($request->only(['punchIn', 'punchOut', 'comments']));

        //更新後のリダイレクト
        return redirect()->route('times.detail', $time->id)->with('success', '打刻データが更新されました');
    }
        
    public function monthlyReport(Request $request)
    {
        // 現在の年と月を取得
    $year = $request->input('year', Carbon::now()->year);
    $month = $request->input('month', Carbon::now()->month);

    // 該当する月のデータを取得
    $times = Time::whereYear('punchIn', $year)
                ->whereMonth('punchIn', $month)
                ->get();

    // 各日の合計勤務時間と実労働時間を計算
    foreach ($times as $time) {
        $punchIn = Carbon::parse($time->punchIn);
        $punchOut = $time->punchOut ? Carbon::parse($time->punchOut) : null;
        
        // 勤務時間の初期化
        $totalWorkingTime = 0;

        if ($punchOut) {
            // 出勤時間と退勤時間の差（分）を計算して勤務時間に変換
            $totalWorkingTime = $punchIn->diffInMinutes($punchOut) / 60;

            // 休憩時間がある場合、勤務時間から休憩時間を引く
            if ($time->break_time) {
                $breakTime = Carbon::parse($time->break_time)->hour + (Carbon::parse($time->break_time)->minute / 60);
                $totalWorkingTime -= $breakTime;
            }
        }

        // 実労働時間を新しいフィールドに格納（例: 実労働時間を分または時間に変換して表示）
        $time->actual_working_time = number_format($totalWorkingTime, 1) . '時間';
    }

       // ビューにデータを渡す
       return view('monthly_report.monthly', compact('year', 'month', 'times'));
    }

}
