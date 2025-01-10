<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MonthlyReport Model
 *
 * ユーザーの月次報告データを管理するモデルです。
 * 報告の状態や作成者とのリレーションを管理します。
 *
 * @package App\Models
 */
class MonthlyReport extends Model
{
    use HasFactory;

    /**
     * このモデルが関連付けられるテーブル名。
     *
     * @var string
     */
    // テーブル名
    protected $table = 'monthly_reports';


    /**
     * 一括代入可能な属性。
     *
     * @var array
     */
    // フィルタリング可能なカラム
    protected $fillable = [
        'user_id', // 報告を作成したユーザーID
        'month', // 報告対象の月
        'status', // 報告の状態（pending, approved, rejected）
    ];

    /**
     * 月次報告を作成したユーザーを取得します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *     ユーザーとのリレーションを返します。
     */
    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 報告の状態（status）を日本語に変換して取得します。
     *
     * @return string
     *     日本語に変換された報告状態（保留中, 承認済み, 却下, 不明な状態）。
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
