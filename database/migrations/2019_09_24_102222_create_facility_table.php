<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('code', 5);
            $table->string('contact_person', 30);
            $table->string('contact_person_phone', 20);
            $table->string('server_ip', 20);
            $table->string('online_url', 55);
            $table->string('email', 30);
            $table->tinyInteger('state_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facility');
    }
}
