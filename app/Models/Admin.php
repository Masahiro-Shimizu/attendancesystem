<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Authenticatable クラスを使用
use Illuminate\Notifications\Notifiable; // Notifiable トレイトを正しい名前空間からインポート

/**
 * Admin Model
 *
 * 管理者認証用のモデルです。
 * Laravelの認証システムを管理者ガードで利用可能にします。
 *
 * @package App\Models
 */
class Admin extends Authenticatable // 管理者用にAuthenticatableを継承
{
    use Notifiable;

    /**
     * 管理者用の認証ガードを指定します。
     *
     * @var string
     */
    protected $guard = 'admin'; // ガードをadminに設定

    /**
     * 
     *　一括代入可能な属性。
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * 配列やJSONに変換する際に隠す属性。
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
