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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('vid', 128)->unique();
//            $table->timestamp('created_at')->useCurrent();
//            $table->timestamp('updated_at')->nullable();
//            $table->integer('duration')->unsigned()->default(0);
//            $table->smallInteger('res_w')->unsigned()->default(0);
//            $table->smallInteger('res_h')->unsigned()->default(0);
//            $table->integer('size')->unsigned()->default(0);
//            $table->string('codec_name', 20)->nullable();
//            $table->string('format_name', 20)->nullable();
//            $table->comment('Repozytorium wideo');
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
