<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Notification Model
 *
 * ユーザーへの通知データを管理するモデルです。
 * 通知の種類、内容、既読状態を保存します。
 *
 * @package App\Models
 */
class Notification extends Model
{
    use HasFactory;

    /**
     * 一括代入可能な属性。
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_checked',
    ];
}
