<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Time Model
 *
 * 勤怠データを管理するモデルです。
 * 出勤時間、退勤時間、休憩時間、コメントを保存・取得します。
 *
 * @package App\Models
 */
class Time extends Model
{
    use HasFactory;

    /**
     * モデルが関連付けられるテーブル名。
     *
     * @var string
     */
    protected $table = 'times';

    /**
     * 一括代入を許可する属性。
     *
     * @var array
     */
    protected $fillable = 
    [
        'user_id',    // 勤怠データに紐づくユーザーID
        'punchIn',   // 出勤時刻
        'punchOut',    // 退勤時刻
        'comments',   // コメント
        'break_time', // 休憩時間（分単位）
    ];

    /**
     * 属性のキャスト設定。
     *
     * @var array
     */
    // punchIn と punchOut を自動的に Carbon インスタンスに変換
    protected $casts = [
        'punchIn' => 'datetime',    // 出勤時刻を Carbon インスタンスにキャスト
        'punchOut' => 'datetime',   // 退勤時刻を Carbon インスタンスにキャスト
        'break_time' => 'integer',  // 休憩時間を整数としてキャスト
    ];
}
