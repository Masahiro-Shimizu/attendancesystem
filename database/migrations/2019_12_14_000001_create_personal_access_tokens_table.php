<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreatePersonalAccessTokensTable Migration
 *
 * Sanctum パッケージが利用する personal_access_tokens テーブルを作成します。
 * このテーブルは API トークンの管理に使用されます。
 *
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * マイグレーションを実行して personal_access_tokens テーブルを作成します。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->morphs('tokenable'); // 多態リレーション
            $table->string('name'); // トークンの名前
            $table->string('token', 64)->unique(); // トークンのユニークな識別子
            $table->text('abilities')->nullable(); // トークンの許可（スコープ）
            $table->timestamp('last_used_at')->nullable(); // 最後に使用された日時
            $table->timestamp('expires_at')->nullable(); // トークンの有効期限
            $table->timestamps(); // 作成日時と更新日時
        });
    }

    /**
     * マイグレーションをロールバックして personal_access_tokens テーブルを削除します。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
