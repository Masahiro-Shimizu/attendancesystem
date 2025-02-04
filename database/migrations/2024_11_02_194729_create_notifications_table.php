<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateNotificationsTable Migration
 *
 * ユーザーへの通知を管理する notifications テーブルを作成します。
 * 通知の種類、メッセージ、確認状態を記録し、通知機能を提供します。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して notifications テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->unsignedBigInteger('user_id'); // 通知対象のユーザーID
            $table->string('type'); // 通知の種類（例: leave_rejected, monthly_report_approved）
            $table->text('message'); // 通知メッセージ
            $table->boolean('is_checked')->default(false); // 通知の確認状態
            $table->timestamps(); // 作成日時と更新日時

            $table->foreign('user_id') // 外部キー制約
                  ->references('id')->on('users')
                  ->onDelete('cascade');// ユーザー削除時に通知も削除
        });
    }

    /**
     * マイグレーションをロールバックして notifications テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
