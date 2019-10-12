<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity', 2000);
            $table->tinyInteger('user_id');
            $table->string('facility', 10);
            $table->string('status', 500)->nullable();
            $table->string('previous_status', 500)->nullable();
            $table->string('activity_date', 50);
            $table->string('week', 20);
            $table->string('date_submitted', 20);
            $table->string('month', 20);
            $table->string('year', 20);
            $table->string('day', 20);
            $table->string('visit_type', 100);
            $table->string('comments', 200)->nullable();
            $table->string('unplanned', 555)->nullable();
            $table->string('planned', 555)->nullable();
            $table->string('unresolved', 555)->nullable();
            $table->string('issues', 555)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity');
    }
}
