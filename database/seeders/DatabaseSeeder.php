<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // ユーザーを10人作成
        \App\Models\User::factory()->count(10)->create();

        // 管理者を1人作成
        \App\Models\User::factory()->state([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'), // 管理者のパスワード
            'role' => 'admin',
        ])->create();        


        // ここに他のシーダーがあれば追加
        $this->call(TestAttendanceSeeder::class); // テストデータのシーダーを実行
    }
}
