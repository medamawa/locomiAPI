<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id')->comment('ユーザーID');
            $table->geometry('location')->comment('位置情報');
            $table->string('text')->comment('本文');
            $table->string('image')->nullable()->comment('画像');
            $table->unsignedInteger('release')->comment('公開範囲');
            $table->softDeletes();
            $table->timestamps();

            $table->index('id');
            $table->index('user_id');
            // $table->spatialIndex('location');
            $table->index('text');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comics');
    }
}
