<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimeListController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $time_list = $user->time_lists()->whereDate('created_at', date('Y-m-d'))->first();
        if ($time_list) {
            return redirect()->route('time_lists.index')->with('flash_message', '既に打刻済みです。');
        }
        $request->user()->time_lists()->create([]);
        return redirect()->route('time_lists.index')->with('flash_message', '打刻完了しました。');
    }

}
