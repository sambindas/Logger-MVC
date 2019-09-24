<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facility', 20);
            $table->string('issue_type', 10);
            $table->string('issue_level', 20);
            $table->string('issue', 1000);
            $table->string('filter_issue_date', 50);
            $table->string('issue_date', 50);
            $table->string('issue_client_reporter', 50);
            $table->string('affected_department', 50);
            $table->tinyInteger('support_officer');
            $table->string('priority', 10);
            $table->string('issue_reported_on', 50);
            $table->tinyInteger('status');
            $table->string('resolution_date', 50)->nullable();
            $table->tinyInteger('resolved_by')->nullable();
            $table->string('info_relayed_to', 50)->nullable();
            $table->string('info_medium', 50)->nullable();
            $table->string('month', 20);
            $table->tinyInteger('assigned_to')->nullable();
            $table->tinyInteger('type');
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
        Schema::dropIfExists('issue');
    }
}
