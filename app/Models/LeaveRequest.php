<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

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
