<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create("users", function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->string('personnel_code')->nullable();
            $table->boolean('gender')->nullable();
            $table->string('password');
            $table->boolean('password_change')->default(1);
            $table->boolean('active')->default(0);
            $table->string('avatar')->nullable();
            $table->string('signature')->nullable();
            $table->tinyInteger('system')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("users");
    }
}
