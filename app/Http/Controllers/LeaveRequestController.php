<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\MonthlyReport;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ApprovalHistory;


class LeaveRequestController extends Controller
{
    public function create()
    {
       /**
    　　 * 新しい休暇申請を作成するためのフォームを表示します。
    　　 *
    　　 * @return \Illuminate\View\View
    　　 *     休暇申請作成用のビューを返します。
    　　 */
        //新しい休暇申請を作成するためのフォームを表示します。
        return view('leave_requests.create');
    }

     /**
     * 新しい休暇申請を保存します。
     *
     * @param \Illuminate\Http\Request $request
     *     リクエストインスタンス。休暇申請のデータが含まれます。
     *
     * @return \Illuminate\Http\RedirectResponse
     *     申請一覧ページへリダイレクトし、成功メッセージを表示します。
     *
     * @throws \Illuminate\Validation\ValidationException
     *     バリデーションに失敗した場合に例外が発生します。
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:vacation,paid_leave,absent',//typeは必須、休暇、有給、欠勤のいずれかにする
            'start_date' => 'required|date',//start_dateは必須、日付を入力
            'end_date' => 'nullable|date|after_or_equal:start_date',//end_dateは日付を入力、start_date以降
            'reason' => 'nullable|string',//理由は任意、文字列で入力
        ]);

        //Leave_requestモデルを使用して新しい申請を保存。
        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        //申請一覧ページにリダイレクトする
        return redirect()->route('leave_requests.index')->with('success', '申請が送信されました。');
    }

     /**
     * 現在ログイン中のユーザーの休暇申請一覧を表示します。
     *
     * @return \Illuminate\View\View
     *     休暇申請の一覧ページを表示します。
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())->get();//現在ログイン中のユーザーの休暇申請一覧を取得して表示する

        return view('leave_requests.index', compact('leaveRequests'));//ユーザーの申請一覧を表示する。
    }

    /**
     * 休暇申請を承認し、履歴を保存します。
     *
     * @param int $id
     *     承認する休暇申請のID。
     *
     * @return \Illuminate\Http\RedirectResponse
     *     管理者ダッシュボードへリダイレクトし、成功メッセージを表示します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDの休暇申請が存在しない場合に例外が発生します。
     */
    public function approve($id)
    {
        //LeaveRequestモデルを使用して、指定されたID（$id）に対応するレコードを取得する
        $leaveRequest = LeaveRequest::findOrFail($id);//findOrFailはEloquent ORM のメソッド
        $leaveRequest->update(['status' => 'approved']);//申請のステータスを承認に変更する

        // ApprovalHistoryを使用して承認履歴の保存
        ApprovalHistory::create([
            'application_id' => $id,
            'application_type' => LeaveRequest::class,
            'admin_id' => auth()->id(),
            'action' => 'approved',
            'comment' => '承認しました。',
        ]);

        //return resonse()->json([
            //'message' => '申請を承認しました。',
            //'status' => '承認済み'
        //]);

        // 承認完了後に管理者ダッシュボードへリダイレクト
        return redirect()->route('admin.home')->with('success', '申請を承認しました。');
    }

    /**
     * 休暇申請を却下し、コメントを保存し、通知を送信します。
     *
     * @param \Illuminate\Http\Request $request
     *     リクエストインスタンス。却下コメント（reject_comment）が含まれます。
     * @param int $id
     *     却下する休暇申請のID。
     *
     * @return \Illuminate\Http\RedirectResponse
     *     元のページへリダイレクトし、成功メッセージを表示します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDの休暇申請が存在しない場合に例外が発生します。
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_comment' => 'nullable|string|max:500',
        ]);

        //指定されたIDの休暇申請を取得、存在しない場合は404エラーを返す
        $leaveRequest = LeaveRequest::findOrFail($id);

        //ステータスを却下に更新
        $leaveRequest->status = 'rejected';
        $leaveRequest->reject_comment = $request->input('reject_comment');
        $leaveRequest->rejected_by = auth()->user()->name;
        $leaveRequest->save();

        // 申請タイプに応じたラベルを作成
        $leaveType = '';
        if ($leaveRequest->type == 'paid_leave') {
            $leaveType = '有給休暇';
            } elseif ($leaveRequest->type == 'vacation') {
            $leaveType = '休暇';
            } elseif ($leaveRequest->type == 'absence') {
            $leaveType = '欠勤';
        }

        // 通知の作成
        Notification::create([
            'user_id' => $leaveRequest->user_id,
            'type' => 'leave_rejected', // ここでtypeを指定
            'message' => "{$leaveType}申請が却下されました。理由: " . $leaveRequest->reject_comment,
            'is_checked' => false,
        ]);

        return redirect()->back()->with('success', '申請を差し戻しました');
    }

    /**
    * 休暇申請を却下し、コメント、履歴、および通知を作成します。
    *
    * @param int $id
    *     却下する休暇申請のID。
    * @param \Illuminate\Http\Request $request
    *     リクエストインスタンス。却下コメントが含まれます。
    *
    * @return \Illuminate\Http\RedirectResponse
    *     リダイレクトして成功メッセージを表示します。
    *
    * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
    *     指定されたIDの休暇申請が見つからない場合に発生します。
    *
    * @example
    *     POST /leave-requests/{id}/reject
    *     リクエストデータ: ['reject_comment' => '不適切な理由による却下']
    */
    public function rejectLeaveRequest($id, Request $request)
    {
        $request->validate([
            'reject_comment' => 'nullable|string|max:500',
        ]);

        // 却下する処理
        $leaveRequest = LeaveRequest::find($id);
        $leaveRequest->status = 'rejected';
        $leaveRequest->reject_comment = $request->input('reject_comment');
        $leaveRequest->save();

       // 承認履歴の保存
       ApprovalHistory::create([
            'application_id' => $id,
            'application_type' => LeaveRequest::class,
            'admin_id' => auth()->id(),
            'action' => 'rejected',
            'comment' => $request->input('reject_comment'),
        ]);

        // 通知を作成
        Notification::create([
            'user_id' => $leaveRequest->user_id,
            'type' => 'leave_rejected', // 申請却下のタイプ
            'message' => '申請が却下されました。理由: ' . $leaveRequest->reject_comment,
            'is_checked' => 0,
        ]);

        return redirect()->back()->with('success', '申請を却下しました。');
    }
}

