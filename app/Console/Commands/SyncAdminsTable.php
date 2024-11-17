<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Admin;

class SyncAdminsTable extends Command
{
    // コマンドのシグネチャ（ターミナルで使用するコマンド名）
    protected $signature = 'sync:admins';

    // コマンドの説明
    protected $description = 'Sync users with admin role to the admins table';

    // コマンドの実行ロジック
    public function handle()
    {
        // usersテーブルからroleがadminのデータを取得
        $users = User::where('role', 'admin')->get();

        foreach ($users as $user) {
            // adminsテーブルにすでに存在しない場合のみ追加
            if (!Admin::where('email', $user->email)->exists()) {
                Admin::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password, // 既存のハッシュ化されたパスワードを使用
                    'role' => 'admin',
                ]);
                $this->info("Added admin: {$user->email}");
            } else {
                $this->info("Admin already exists: {$user->email}");
            }
        }

        $this->info('Admins table sync complete.');
    }
}
