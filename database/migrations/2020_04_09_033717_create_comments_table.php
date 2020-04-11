<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('user_id')->comment('ユーザーID');
            $table->string('comic_id')->comment('コミックID');
            $table->string('text')->comment('本文');
            $table->softDeletes();
            $table->timestamps();

            $table->index('uuid');
            $table->index('user_id');
            $table->index('comic_id');

            $table->foreign('user_id')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('comic_id')
                ->references('uuid')
                ->on('comics')
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
        Schema::dropIfExists('comments');
    }
}
