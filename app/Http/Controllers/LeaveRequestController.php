<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\MonthlyReport;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function create()
    {
        return view('leave_requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:vacation,paid_leave,absent',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leave_requests.index')->with('success', '申請が送信されました。');
    }

    public function index()
    {
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())->get();

        return view('leave_requests.index', compact('leaveRequests'));
    }

    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'approved']);

        return response()->json([
            'message' => '申請を承認しました。',
            'status' => '承認済み'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_comment' => 'nullable|string|max:500',
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->status = 'rejected';
        $leaveRequest->reject_comment = $request->input('reject_comment');
        $leaveRequest->rejected_by = auth()->user()->name;
        $leaveRequest->save();

        // 通知を作成
        Notification::create([
            'user_id' => $leaveRequest->user_id,
            'type' => 'leave_rejected', // ここでtypeを指定
            'message' => "休暇申請が却下されました。理由: " . $request->input('reject_comment'),
            'is_checked' => false,
        ]);

        return redirect()->back()->with('success', '申請を差し戻しました');
    }
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

