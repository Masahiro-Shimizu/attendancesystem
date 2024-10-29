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
        if (!Schema::hasTable('admins')) {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();  // 管理者のID（自動インクリメント）
            $table->string('name');  // 管理者の名前
            $table->string('email')->unique();  // 管理者のメールアドレス（重複不可）
            $table->string('password');  // パスワード
            $table->string('role')->default('admin'); // 役割（デフォルトは 'admin'）
            $table->rememberToken();  // Remember me トークン
            $table->timestamps();  // 作成・更新日時
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
