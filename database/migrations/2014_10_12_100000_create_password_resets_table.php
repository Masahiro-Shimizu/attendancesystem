<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreatePasswordResetsTable Migration
 *
 * パスワードリセット機能に使用する password_resets テーブルを作成します。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して password_resets テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();       // リセット対象のメールアドレス
            $table->string('token');               // パスワードリセットトークン
            $table->timestamp('created_at')->nullable(); // リセットリクエストの作成日時
        });
    }

    /**
     * マイグレーションをロールバックして password_resets テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
};
