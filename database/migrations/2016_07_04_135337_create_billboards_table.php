<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billboards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20);
            $table->text('description');
            $table->integer('type');
            $table->integer('anonymous');
            $table->integer('adult');
            $table->string('domain',20);
            $table->integer('status');
            $table->integer('target');
            $table->integer('limit_college');
            $table->integer('college_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billboards');
    }
}
