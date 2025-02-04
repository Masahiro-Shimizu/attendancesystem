<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateMonthlyReportsTable Migration
 *
 * 月次報告データを管理する monthly_reports テーブルを作成します。
 * 各ユーザーが提出する月報の状態や適用月を記録します。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して monthly_reports テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->unsignedBigInteger('user_id'); // 外部キー: users テーブルの id を参照
            $table->date('month'); // 月報が適用される月
            $table->string('status') // 月報の状態
                  ->default('pending'); // "pending", "approved", "rejected" // デフォルト: 承認待ち
            $table->timestamps(); // 作成日時と更新日時

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // ユーザー削除時に対応する月報データも削除
        });
    }

    /**
     * マイグレーションをロールバックして monthly_reports テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_reports');
    }
};
