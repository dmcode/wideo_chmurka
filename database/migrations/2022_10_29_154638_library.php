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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->string('lid', 128)->unique();
//            $table->bigInteger('user_id')->unsigned();
//            $table->foreign('user_id')->references('id')->on('users');
//            $table->bigInteger('video_id')->unsigned();
//            $table->foreign('video_id')->references('id')->on('video');
//            $table->enum('visibility', ['private', 'protected', 'public'])->default('private');
//            $table->integer('number_views')->unsigned()->default(0);
//            $table->date('published_at')->nullable();
//            $table->string('thumb', 128)->nullable();
//            $table->string('title', 100)->nullable();
//            $table->string('description', 1000)->nullable();
//            $table->unique(['user_id', 'video_id'], 'uidx_user_video');
//            $table->comment('Biblioteka wideo użytkownika');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
