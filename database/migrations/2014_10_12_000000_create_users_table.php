<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('screen_name')->unique()->nullable(false)->comment('アカウント名');
            $table->string('name')->nullable(false)->comment('ユーザー名');
            $table->string('profile_image')->nullable()->comment('プロフィール画像のパス');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable()->comment('メールアドレス変更日時');
            $table->string('password')->comment('パスワード');
            $table->integer('login_change_count')->nullable()->comment('ログイン試行回数');
            $table->timestamp('login_change_data')->nullable()->comment('ログイン試行日時');
            $table->rememberToken();
            $table->timestamps();

            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
