<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateTimesTable Migration
 *
 * ユーザーの勤怠データを保存する times テーブルを作成します。
 * このテーブルには、出勤時間、退勤時間、ユーザーとの関連付けが記録されます。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して times テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->unsignedBigInteger('user_id'); // 外部キー: users テーブルの id を参照
            $table->dateTime('punchIn')->nullable(); // 出勤時刻（任意）
            $table->dateTime('punchOut')->nullable(); // 退勤時刻（任意）
            $table->timestamps();  // 作成日時と更新日時
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // 親ユーザーが削除された場合、対応する勤怠データも削除
        });
        
    }

    /**
     * マイグレーションをロールバックして times テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('times');
    }
};
