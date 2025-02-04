<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * AddMethodAndCommentsToTimesTable Migration
 *
 * times テーブルに打刻方法 (method) とコメント (comments) カラムを追加します。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して times テーブルに新しいカラムを追加します。
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
     * マイグレーションをロールバックして times テーブルから追加したカラムを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::table('times', function (Blueprint $table) {
            if (Schema::hasColumn('times', 'comments')) {
                $table->dropColumn('comments');// コメントカラムの削除
            }
            if (Schema::hasColumn('times', 'method')) {
                $table->dropColumn('method');         // 打刻方法カラムの削除
            }
            // 他のカラムを削除するロジックがあればそれも記述します。
        });
    }
};
