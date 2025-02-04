<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddBreakTimeToTimesTable Migration
 *
 * 勤務中の休憩時間を記録するために times テーブルに break_time カラムを追加します。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して times テーブルに break_time カラムを追加します。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('times', function (Blueprint $table) {
            $table->time('break_time') // 休憩時間を保存するカラム
            ->nullable() // NULL 値を許容
            ->after('punchOut'); // 休憩時間のカラムを追加
        });
    }

    /**
     * マイグレーションをロールバックして times テーブルから break_time カラムを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::table('times', function (Blueprint $table) {
            $table->dropColumn('break_time'); // ロールバック時にカラムを削除
        });
    }
};
