<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('items', function (Blueprint $table) {
            $table->increments('id');

            //$table->integer('type'); // 1 au 2 sell 3. group
            $table->string('name',50);
            $table->integer('price');
            $table->text('description');

            $table->boolean('new');
            $table->boolean('free');
            //$table->integer('target'); // classmate, , samemajor, college, student, 
            $table->integer('disabled'); // 0 1
            $table->integer('hitpoint'); 
            $table->integer('user_id')->unsigned();
            //$table->integer('college_id')->unsigned();
            //$table->integer('album_id')->unsigned();
            //$table->string('report_id');
            $table->integer('category_id')->unsigned();
            $table->integer('target'); //0 = female, 1 = men
            //$table->integer('comment_id');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('type');
            $table->integer('repost');
            
            $table->boolean('notification');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            //$table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');Report

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
