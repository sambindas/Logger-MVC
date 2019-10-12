<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_role', 15)->nullable();
            $table->string('user_name', 50);
            $table->string('email', 70);
            $table->string('phone', 15);
            $table->string('password', 50);
            $table->dateTime('date_added');
            $table->string('online_status', 50)->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('online')->nullable();
            $table->tinyInteger('user_type');
            $table->tinyInteger('state_id');
            $table->tinyInteger('facility_id')->nullable();
            $table->string('password_token')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
