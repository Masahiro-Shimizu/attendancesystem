<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddRoleToUsersTable Migration
 *
 * ユーザーの役割を管理するために users テーブルに role カラムを追加します。
 *
 * @package Database\Migrations
 */
class AddRoleToUsersTable extends Migration
{
    /**
     * マイグレーションを実行して users テーブルに role カラムを追加します。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role') // 役割を保存するカラム
            ->default('user') // デフォルト値は 'user'
            ->after('password');  // roleカラムを追加
        });
    }

    /**
     * マイグレーションをロールバックして users テーブルから role カラムを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');  // ロールカラムを削除
        });
    }
}
