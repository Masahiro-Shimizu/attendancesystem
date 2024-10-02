<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Time extends Model
{
    protected $table = 'times';
    protected $fillable = ['user_id','punchIn','punchOut', 'comments'];

    // punchIn と punchOut を自動的に Carbon インスタンスに変換
    protected $casts = [
        'punchIn' => 'datetime',
        'punchOut' => 'datetime',
    ];
}
