<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
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

    public function reject($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'rejected']);

        return response()->json([
            'message' => '申請を却下しました。',
            'status' => '却下'
        ]);
    }
}

