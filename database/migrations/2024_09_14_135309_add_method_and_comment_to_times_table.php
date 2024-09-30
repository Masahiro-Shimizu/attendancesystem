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
        Schema::table('times', function (Blueprint $table) {
            $table->string('method')->default('自動'); // 打刻方法
            $table->text('comments')->nullable();    // コメント
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('times', function (Blueprint $table) {
            if (Schema::hasColumn('times', 'comments')) {
                $table->dropColumn('comments');
            }
            // 他のカラムを削除するロジックがあればそれも記述します。
        });
    }
};
