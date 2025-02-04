<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateAdminsTable Migration
 *
 * 管理者データを保存する admins テーブルを作成します。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して admins テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->timestamps(); // 作成日時と更新日時
        });
    }

    /**
     * マイグレーションをロールバックして admins テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
