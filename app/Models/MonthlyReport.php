<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory;

    // テーブル名
    protected $table = 'monthly_reports';

    // フィルタリング可能なカラム
    protected $fillable = [
        'user_id',
        'month',
        'status',
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
