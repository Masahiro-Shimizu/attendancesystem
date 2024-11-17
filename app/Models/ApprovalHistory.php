<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'application_type',
        'admin_id',
        'action',
        'comment',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function application()
    {
        // 多態的関連のための定義
        return $this->morphTo();
    }
}
