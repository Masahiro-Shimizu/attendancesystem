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

/**
 * TimesController
 *
 * 勤怠管理の出退勤・休憩機能、勤怠データの表示・編集、月次報告を提供するコントローラ。
 *
 * @package App\Http\Controllers
 */
class TimesController extends Controller
{
    /**
     * ユーザーのホーム画面に表示する勤怠データを取得します。
     *
     * @return \Illuminate\View\View
     *     最新の勤怠データ、差し戻された月報・休暇申請、通知データをビューに渡します。
     */
    //userとしてログインした際に動作するメソッド
    public function index()
    {
        //$today = Carbon::today()->startOfDay(); //←このコードは冗長

        $items = Time::where('user_id', Auth::id()) //「items」という変数の中に「Time」モデルから'user_id'が'Auth_id'と一致するレコードをフィルタリング
            ->orderBy('punchIn', 'desc') //'punchIn'を取得して降順に並び替える
            ->get() //書き込みを実行し、並び替えたものを取得
            ->groupBy(function($date) { //取得した勤怠データを、'punchIn'の日付(Y-m-d形式)ごとにグループ化
                return Carbon::parse($date->punchIn)->format('Y-m-d'); //Carbon::parse()は日付の操作やフォーマットへの変更を可能にする　Carbonオブジェクト
            })
            ->take(3); //3日分を取得
        
        //MonthlyReportモデルから、ステータスが'reject（拒否）'のデータを取得する（差し戻しになったデータ）
        $rejectedMonthlyReports = MonthlyReport::where('user_id', auth()->id())
                                 ->where('status', 'rejected')
                                 ->get();

        //LearveRequestモデルから、ステータスが'reject（拒否）'のデータを取得する（差し戻しになったデータ）
        $rejectedLeaveRequests = LeaveRequest::where('user_id', auth()->id())
                               ->where('status', 'rejected')
                               ->get();

        //Notificationモデルから、未確認のステータスになっているデータを取得する
        $notifications = Notification::where('user_id', auth()->id())
                       ->where('is_checked', false)
                       ->orderBy('created_at', 'desc')
                       ->get();

        //compactでまとめた4つの変数を配列としてまとめ、'home'のviewにデータを送っている
        return view('home', compact('items', 'rejectedMonthlyReports', 'rejectedLeaveRequests', 'notifications')); //compact()は指定した変数名をキー、変数の値を値として連想配列を作成するための関数です。
    }

    /**
     * 出勤ボタン押下時の処理を実行します。
     *
     * @return \Illuminate\Http\JsonResponse
     *     出勤時刻を記録し、成功メッセージを返します。
     */
    //「出勤」ボタンを押下した際に動作するメソッド
    public function punchIn()
    {
        $currentTime = Carbon::now('Asia/Tokyo'); //現在の日時を「東京」の時間で取得
    \Log::info('PunchIn Time:', ['time' => $currentTime]);//取得した現在時刻をログに出力する

    try {
        $timeEntry = Time::create([ //Timeモデルを使用してデータベースに新しい勤怠を挿入する
            'user_id' => Auth::id(), //現在ログイン中のユーザーのidに（Auth::id()）を設定
            'punchIn' => $currentTime, //punchInに$currentTimeの値を設定する　$currentTimeはCarbon::now() によって生成された日時情報を持つオブジェクト
        ]);

        \Log::info('Time Entry Created:', ['entry' => $timeEntry->toArray()]);
    } catch (\Exception $e) {
        \Log::error('Failed to create Time Entry:', ['error' => $e->getMessage()]);
    }

        return response()->json(['message' => '出勤しました']);
    }

    /**
     * 退勤ボタン押下時の処理を実行します。
     *
     * @return \Illuminate\Http\JsonResponse
     *     退勤時刻を記録し、成功メッセージを返します。
     */
    public function punchOut()
    {
        \Log::info('PunchOut method called for user:', ['user_id' => Auth::id()]);

        $attendance = Time::where('user_id', Auth::id()) //「attendance」という変数の中に「Time」モデルから'user_id'が'Auth_id'と一致するレコードをフィルタリング
                            ->whereNull('punchOut') //「punchOut」がNULL(記録にない＝現在出勤中)のレコードを取得
                            ->orderBy('punchIn', 'desc') //「punchIn」退勤が「first」最新のものを取得
                            ->first();

                            if ($attendance) { //出勤中のレコードが見つかった場合に処理を続行
                                $currentTime = Carbon::now('Asia/Tokyo');
                                \Log::info('Updating PunchOut time for user:', ['user_id' => Auth::id(), 'time' => $currentTime]);
                        
                                try {
                                    $attendance->update([ //attendanceのpunchoutカラムに現在時刻（$currentTime）を記録
                                        'punchOut' => $currentTime,
                                    ]);
                        
                                    \Log::info('Updated Attendance Record:', ['attendance' => $attendance->toArray()]); //データベースのレコードを更新する
                                } catch (\Exception $e) {
                                    \Log::error('Failed to update PunchOut:', ['error' => $e->getMessage()]);
                                }
        
                            return response()->json(['message' => '退勤しました']); //退勤しましたをjsonレスポンスで返す
        }
    
        return response()->json(['message' => '退勤処理に失敗しました'], 400);
    }

    /**
     * 休憩開始を記録します。
     *
     * @return \Illuminate\Http\JsonResponse
     *     休憩開始時刻を記録し、成功メッセージを返します。
     */
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
            $times->break_start = Carbon::now();

            // 保存前のデータをログに出力
            Log::info('保存前の勤怠データ: ', ['times' => $times]);

            $times->save();

            // 保存後のデータをログに出力
            Log::info('保存後の勤怠データ: ', ['times' => $times]);
            return response()->json(['message' => '休憩を開始しました']);
        }

        Log::error('休憩開始処理に失敗しました: User ID', ['user_id' => Auth::id()]);
        return response()->json(['message' => '休憩開始処理に失敗しました'], 400);
    }

    /**
     * 休憩終了を記録します。
     *
     * @return \Illuminate\Http\JsonResponse
     *     休憩時間を加算し、成功メッセージを返します。
     */
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
    
    public function showCalendar(Request $request)
    {
        return view('calendar');
    }

    public function getIdByDate(Request $request)
    {
        //日付の取得
        $date = $request->input('date');//リクエストから日付を取得する
        $times = Time::where('user_id', Auth::id())//現在ログイン中のユーザーの勤怠データのみを取得
                      ->whereDate('punchIn', $date)//whereDate()は日付データの「日付部分」のみを比較します　勤怠データのpunchIn指定された日付（$date）と一致するレコードを取得
                      ->first();//条件に一致する最初のレコードを取得

        //勤怠データが取得できた場合
        if ($times) {//
            return response()->json(['status' => 'success', 'data' => ['id' => $times->id]]);//勤怠データidを含む成功応答をJSON形式で返す
        }

        //勤怠データが受け取れなかった場合
        return response()->json(['status' => 'error', 'message' => '指定された日の勤怠データが見つかりません']);
    }

    public function detail($id, Request $request)
    {
        //メソッドが呼び出されたことを記録するログ（勤怠データのIDを取得）
        \Log::info('detail method called with id:', ['id' => $id]);

        // `$id` に基づく勤怠データを取得
        $times = Time::find($id);
    
        //該当するデータが見つからない場合の処理
        if (!$times) {
            return redirect()->back()->with('error', '該当の勤怠データが見つかりません。');//redirect()->back()で前のページにリダイレクトする
        }

        // `$date`がない場合、通常のビューを返す
        return view('detail', compact('times'));
    }

    public function detailByDate($date)
    {
       // 日付とログイン中のユーザーに基づいて勤怠データを取得
       $attendance = Time::where('user_id', Auth::id())
                      ->whereDate('punchIn', $date)
                      ->first();

       // 勤怠データが存在しない場合の処理
       if (!$attendance) {
           return redirect()->route('times.calendar')->with('error', '指定された日の勤怠データが見つかりません');
       }

       // ビューにデータを渡して表示
       return view('times.detail', compact('times'));
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

    /**
     * 勤怠データを編集・更新します。
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'punchIn' => 'required|date',
            'punchOut' => 'nullable|date',
            'comments' => 'nullable|string|max:255',
            'method' => 'required|string',
            'break_time' => 'nullable|integer|min:0', 
        ]);
    
        // 更新する打刻データを取得
        $time = Time::findOrFail($id);
    
        // データを更新
        $time->update([
            'punchIn' => $request->input('punchIn'),
            'punchOut' => $request->input('punchOut'),
            'method' => $request->input('method'),
            'comments' => $request->input('comments'),
            'break_time' => $request->input('break_time'), 
        ]);
    
        // リクエストからコメントを含む他のフィールドも更新
        $time->update($request->only(['punchIn', 'punchOut', 'comments']));

        // 更新後のリダイレクト
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
