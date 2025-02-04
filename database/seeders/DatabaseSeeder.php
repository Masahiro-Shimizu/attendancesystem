<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * データベースを初期化するためのシーダークラス。
 * デフォルトのユーザーや管理者を作成し、必要に応じて他のシーダーを実行します。
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * データベースを初期化して、必要なデフォルトデータやテストデータを挿入します。
     *
     * @return void
     */
    public function run(): void
    {
        // ユーザーを10人作成
        \App\Models\User::factory()->count(10)->create();

        // 管理者を1人作成
        \App\Models\User::factory()->state([
            'name' => 'Admin User', // 管理者の名前
            'email' => 'admin@example.com', // 管理者のメールアドレス
            'password' => bcrypt('adminpassword'), // 管理者のパスワード
            'role' => 'admin', // 管理者の役割
        ])->create();        


        // ここに他のシーダーがあれば追加
        $this->call(TestAttendanceSeeder::class); // テストデータのシーダーを実行
    }
}
