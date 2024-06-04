<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimeListController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $month = intval($today->month);
        $day = intval($today->day);

        //当日の勤怠を取得
        $items = Time::GetMonthAttendance($month)->GetDayAttendance($day)->get();
        return view('time.index',['item'=>$items]);
    }

    public function store()
    {
        $user = auth()->user();
        $today = $user->time_lists()->whereDate('created_at', date('Y-m-d'))->first();
        if ($today) {
            return redirect()->route('time_lists.index')->with('flash_message', '既に打刻済みです。');
        }
        $user->time_lists()->create([]);
        return redirect()->route('time_lists.index')->with('flash_message', '打刻完了しました。');
    }

    public function lists()
    {
        
    }

}
