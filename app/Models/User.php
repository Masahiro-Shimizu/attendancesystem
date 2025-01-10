<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 *
 * Laravel の認証機能を利用するためのモデルです。
 * API トークン認証や通知機能をサポートします。
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 一括代入を許可する属性。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // ユーザーの名前
        'email', // ユーザーのメールアドレス
        'password', // ハッシュ化されたパスワード
    ];

    /**
     * シリアライズ時に隠す属性。
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', // パスワード（セキュリティのため非公開）
        'remember_token', // セッション管理用トークン
    ];

    /**
     * 属性の型キャスト設定。
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // メール確認日時を Carbon インスタンスにキャスト
    ];
}
