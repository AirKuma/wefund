<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbmessengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fbmessengers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender_id');
            $table->boolean('is_subscription');
            $table->string('college_acronym');
            $table->date('send_at');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fbmessengers');
    }
}
