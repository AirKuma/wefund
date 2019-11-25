<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thread_participant', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thread_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('thread_participant');
    }
}
