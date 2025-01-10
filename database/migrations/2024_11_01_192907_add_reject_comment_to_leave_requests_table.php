<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddRejectCommentToLeaveRequestsTable Migration
 *
 * leave_requests テーブルに却下理由を保存する reject_comment カラムを追加します。
 * 管理者が申請を却下した際に、その理由を記録するために使用されます。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して leave_requests テーブルに reject_comment カラムを追加します。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->text('reject_comment') // 却下理由を保存するカラム
                  ->nullable() // NULL 値を許容
                  ->after('status');  // status カラムの後に追加
        });
    }
    
    
    

    /**
     * マイグレーションをロールバックして leave_requests テーブルから reject_comment カラムを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('reject_comment');
        });
    }
};
