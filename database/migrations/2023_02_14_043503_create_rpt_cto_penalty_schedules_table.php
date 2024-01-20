<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoPenaltySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    
    public function up()
    {
        Schema::create('rpt_cto_penalty_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('cps_prevailing_law',200)->nullable();
            $table->integer('cps_from_year');
            $table->integer('cps_to_year');
            $table->double('cps_penalty_rate',8,3);
            $table->integer('cps_penalty_limitation')->comment('0=No[the monthly increase of 2% is automatic/continuous in the system, 1=Yes[the monthly increase of 2% will STOP');
            $table->double('cps_maximum_penalty',8,3);
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('rpt_cto_penalty_schedules');
    }
}
