<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddRejectedByToMonthlyReportsTable Migration
 *
 * monthly_reports テーブルに月報申請を却下した管理者の名前を記録する rejected_by カラムを追加します。
 * このカラムにより、却下された月報申請に対して「誰が却下したか」を追跡できます。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して monthly_reports テーブルに rejected_by カラムを追加します。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->string('rejected_by') // 却下した管理者の名前を記録
                  ->nullable() // NULL 値を許容
                  ->after('reject_comment'); // reject_comment カラムの後に配置
        });
    }

    /**
     * マイグレーションをロールバックして monthly_reports テーブルから rejected_by カラムを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->dropColumn('rejected_by');
        });
    }
};
