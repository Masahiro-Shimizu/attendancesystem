<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * LeaveRequest Model
 *
 * ユーザーの休暇申請データを管理するモデルです。
 *
 * @package App\Models
 */
class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可する属性。
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    /**
     * 申請を行ったユーザーを取得します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *     ユーザーモデルとのリレーションを返します。
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 申請の状態（status）を日本語に変換して取得します。
     *
     * @return string
     *     日本語に変換された申請状態（保留中, 承認済み, 却下, 不明な状態）。
     */
    public function getStatusKanjiAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '保留中';
            case 'approved':
                return '承認済み';
            case 'rejected':
                return '却下';
            default:
                return '不明な状態';
        }
    }
}
