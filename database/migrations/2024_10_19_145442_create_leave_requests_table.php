<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CreateLeaveRequestsTable Migration
     *
     * 休暇、有給休暇、欠勤などの申請を管理する leave_requests テーブルを作成します。
     * 各申請は、ユーザー ID に関連付けられ、申請理由、ステータスを含みます。
     *
     * @return void
     */
    public function up()
    {
        /**
         * マイグレーションを実行して leave_requests テーブルを作成します。
         *
         * @return void
         */
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーID
            $table->enum('type', ['vacation', 'paid_leave', 'absent']); // 申請タイプ: 休暇、有給、欠勤
            $table->date('start_date'); // 申請の開始日
            $table->date('end_date')->nullable(); // 終了日（欠勤の場合は1日だけの場合がある）
            $table->text('reason')->nullable(); // 申請理由
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // ステータス
            $table->timestamps();
        });
    }

    /**
     * マイグレーションをロールバックして leave_requests テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
};
