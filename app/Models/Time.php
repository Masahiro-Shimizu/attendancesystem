<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Time extends Model
{
    protected $table = 'times';
    protected $fillable = ['user_id','punchIn','punchOut'];

    /**
     * スコープ：指定した月の勤怠データを取得
     */
    public function scopeGetMonthAttendance(Builder $query, $month)
    {
        return $query->whereMonth('created_at', $month);
    }

    /**
     * スコープ：指定した日の勤怠データを取得
     */
    public function scopeGetDayAttendance(Builder $query, $day)
    {
        return $query->whereDay('created_at', $day);
    }
}
