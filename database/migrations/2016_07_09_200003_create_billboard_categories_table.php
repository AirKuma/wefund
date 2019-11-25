<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillboardCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billboard_categories', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->String('name',15);
            $table->integer('billboard_id')->unsigned();
            $table->timestamps();
            $table->foreign('billboard_id')->references('id')->on('billboards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billboard_categories');
    }
}
