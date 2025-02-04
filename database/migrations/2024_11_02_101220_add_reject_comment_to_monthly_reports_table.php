<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddRejectCommentToMonthlyReportsTable Migration
 *
 * monthly_reports テーブルに差し戻し理由を記録する reject_comment カラムを追加します。
 * 管理者が月報申請を却下した場合、その理由を記録するために使用されます。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して monthly_reports テーブルに reject_comment カラムを追加します。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->text('reject_comment')->nullable()->after('status'); // 差し戻しコメント
        });
    }

    public function down()
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->dropColumn('reject_comment');
        });
    }

};
