<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('major_id')->default(0)->unsigned();
            $table->integer('college_id')->default(0)->unsigned();
            $table->string('username',20);
            $table->string('pseudonym');
            $table->string('firstname',10);
            $table->string('lastname',10);
            $table->string('email')->unique();
            $table->string('other_email')->nullable();
            $table->string('password');
            $table->date('birthday');
            $table->integer('gender');

            $table->string('phone');
            $table->string('avatar');
            //$table->string('facebook_id');
            $table->string('line_username');
            $table->string('telegram_username');
            $table->string('activation_code');
            $table->integer('actived')->default(0);
            $table->date('activated_at');
            $table->date('last_login');
            $table->string('registed_ip');
            $table->string('login_ip');
            $table->integer('permission');
            $table->string('access_token');
            $table->date('read_notification_at');
            $table->rememberToken();
            $table->timestamps();

            // $table->foreign('facebook_id')->references('id')->on('facebooks');
            $table->foreign('major_id')->references('id')->on('majors');
            $table->foreign('college_id')->references('id')->on('colleges');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
