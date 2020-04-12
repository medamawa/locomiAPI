<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->string('following_id')->comment('友達登録しているユーザーID');
            $table->string('followed_id')->comment('友達登録されているユーザーID');
            $table->boolean('request')->default((false));
            $table->boolean('approval')->default(false);
            $table->boolean('block')->default(false);

            $table->index('following_id');
            $table->index('followed_id');

            $table->unique([
                'following_id',
                'followed_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
}
