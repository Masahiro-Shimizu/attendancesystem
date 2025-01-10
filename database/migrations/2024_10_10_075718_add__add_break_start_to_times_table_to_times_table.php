<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddBreakStartToTimesTable Migration
 *
 * 勤務中の休憩開始時刻を記録するために times テーブルに break_start カラムを追加します。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して times テーブルに break_start カラムを追加します。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('times', function (Blueprint $table) {
            $table->dateTime('break_start') // 休憩開始時刻
            ->nullable(); // 休憩開始時刻
        });
    }

    /**
     * マイグレーションをロールバックして times テーブルから break_start カラムを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::table('times', function (Blueprint $table) {
            $table->dropColumn('break_start'); // break_start カラムを削除
        });
    }
};
