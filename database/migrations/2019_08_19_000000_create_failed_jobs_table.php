<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateFailedJobsTable Migration
 *
 * ジョブ処理中に失敗したジョブを記録する failed_jobs テーブルを作成します。
 * このテーブルは、失敗したジョブのデバッグや再実行に使用されます。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して failed_jobs テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();                     // プライマリキー
            $table->string('uuid')->unique(); // 一意識別子
            $table->text('connection');       // 使用された接続名
            $table->text('queue');            // ジョブが登録されたキュー名
            $table->longText('payload');      // ジョブのデータ内容（シリアライズ形式）
            $table->longText('exception');         // 失敗の原因となる例外メッセージ
            $table->timestamp('failed_at')->useCurrent();// ジョブ失敗の日時
        });
    }

    /**
     * マイグレーションをロールバックして failed_jobs テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
};
