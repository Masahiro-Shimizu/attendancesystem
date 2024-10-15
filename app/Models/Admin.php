<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Authenticatable クラスを使用
use Illuminate\Notifications\Notifiable; // Notifiable トレイトを正しい名前空間からインポート

class Admin extends Authenticatable // 管理者用にAuthenticatableを継承
{
    use Notifiable;

    protected $guard = 'admin'; // ガードをadminに設定

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
