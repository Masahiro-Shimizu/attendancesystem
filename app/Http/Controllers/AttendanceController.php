<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Time;
use App\Models\Attendance;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    public function index()
    {
        $today = Carbon::today();
        $month = intval($today->month);
        $day = intval($today->day);

        //当日の勤怠を取得
        $items = Time::GetMonthAttendance($month)->GetDayAttendance($day)->get();
        return view('attendance.index',['item'=>$items]);
    }

    public function punchIn()
    {
        $attendances = Attendance::create([
            'user_id' => Auth::id(),
            'punchin' => Carbon::now('Asia/Tokyo'),
        ]);

        return response()->json(['message' => '出勤しました']);
    }
    
    public function punchOut()
    {
        $attendances = Attendance::where('user_id', Auth::id())
        ->whereNull('punchout')
        ->orderBy('punchin','desc')
        ->first();

        if($attendances)
        {
            $attendances->update([
                'punchout' => Carbon::now('Asia/Tokyo'),
            ]);
            return response()->json(['message' => '退勤しました']);
        }

        return response()->json(['message' => '処理に失敗しました'], 400);
    }
}