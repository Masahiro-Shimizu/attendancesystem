<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_checked = true;
        $notification->save();

        return response()->json(['status' => 'success', 'message' => '通知を確認済みにしました']);
    }

    public function check($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_checked = true;
        $notification->save();

        return redirect()->back()->with('success', '通知を確認済みにしました。');
    }

}
