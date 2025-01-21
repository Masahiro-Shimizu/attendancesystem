<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateApprovalHistoriesTable Migration
 *
 * approval_histories テーブルを作成します。
 * このテーブルは、申請の承認や却下の履歴を記録します。
 * 対象の申請、管理者のアクション、コメントなどを追跡可能にします。
 *
 * @package Database\Migrations
 */
class CreateApprovalHistoriesTable extends Migration
{
    /**
     * マイグレーションを実行して approval_histories テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->unsignedBigInteger('application_id'); // 関連する申請のID
            $table->string('application_type'); // 申請のタイプ (例: MonthlyReport, LeaveRequest)
            $table->unsignedBigInteger('admin_id'); // 承認/差し戻しを行った管理者のID
            $table->string('action'); // "approved" または "rejected"
            $table->text('comment')->nullable(); // 差し戻し理由などのコメント
            $table->timestamps(); // 作成日時と更新日時
        });
    }

    /**
     * マイグレーションをロールバックして approval_histories テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_histories');
    }
}
