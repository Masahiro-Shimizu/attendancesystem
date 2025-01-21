<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * AdminSeeder
 *
 * デフォルトの管理者アカウントを作成するためのシーダークラスです。
 * このクラスは、開発環境や初期セットアップで管理者の初期アカウントを提供します。
 *
 * @package Database\Seeders
 */
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 管理者アカウントを作成
        User::create([
            'name' => 'Admin User', // 管理者の名前
            'email' => 'admin@example.com', // 管理者のメールアドレス
            'password' => Hash::make('password'), // ハッシュ化されたパスワード
            'role' => 'admin',  // 管理者権限を指定
        ]);
    }
}
