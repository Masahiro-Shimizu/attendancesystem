<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * 通知を確認済み（既読）にし、JSONレスポンスを返します。
     *
     * @param int $id
     *     確認済みにする通知のID。
     *
     * @return \Illuminate\Http\JsonResponse
     *     通知確認の成功ステータスとメッセージを含むJSONレスポンスを返します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDの通知が存在しない場合に例外が発生します。
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_checked = true;
        $notification->save();

        return response()->json(['status' => 'success', 'message' => '通知を確認済みにしました']);
    }

    /**
     * 通知を確認済み（既読）にし、前のページにリダイレクトします。
     *
     * @param int $id
     *     確認済みにする通知のID。
     *
     * @return \Illuminate\Http\RedirectResponse
     *     元のページへリダイレクトし、成功メッセージを表示します。
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *     指定されたIDの通知が存在しない場合に例外が発生します。
     */
    public function check($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_checked = true;
        $notification->save();

        return redirect()->back()->with('success', '通知を確認済みにしました。');
    }

}
