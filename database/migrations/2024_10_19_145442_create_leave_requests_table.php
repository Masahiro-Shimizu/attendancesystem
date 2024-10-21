<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
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
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
};
