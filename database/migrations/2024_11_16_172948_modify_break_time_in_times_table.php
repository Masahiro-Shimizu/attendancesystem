<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ModifyBreakTimeInTimesTable Migration
 *
 * times テーブルの break_time カラムの型を変更します。
 * 変更前: TIME型 (時:分:秒)
 * 変更後: INTEGER型 (分単位の整数)
 *
 * @package Database\Migrations
 */
class ModifyBreakTimeInTimesTable extends Migration
{
    /**
     * マイグレーションを実行して times テーブルの break_time カラムを変更します。
     * break_time カラムを TIME型 から INTEGER型 に変更します。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('times', function (Blueprint $table) {
            $table->integer('break_time')->nullable()->change(); // INT型に変更
        });
    }

    /**
     * マイグレーションをロールバックして times テーブルの break_time カラムを元の型に戻します。
     * break_time カラムを INTEGER型 から TIME型 に変更します。
     *
     * @return void
     */
    public function down()
    {
        Schema::table('times', function (Blueprint $table) {
            $table->time('break_time')->nullable()->change(); // 元のTIME型に戻す
        });
    }
}
