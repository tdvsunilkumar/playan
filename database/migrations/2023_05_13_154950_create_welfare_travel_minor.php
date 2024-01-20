<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfareTravelMinor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_welfare_travel_minor', function (Blueprint $table) {
            $table->id();
            $table->integer('wtcm_id')->length(11)->comment('ref-Table: welfare_travel_clearance_minor.wtcm_id');
            $table->integer('req_id')->length(11)->comment('ref-Table: constant.soloparent_requirements');
            $table->string('fwtm_name');
            $table->string('fwtm_type');
            $table->decimal('fwtm_size');
            $table->string('fwtm_path');
            $table->integer('fwtm_is_active')->length(1)->comment('if the status of the application type is active or not');
            $table->integer('created_by')->unsigned()->comment('reference profile.reg_code of the system who create the application type');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Format: [yyyy-mm-dd hh:mm:ss]. ');
            $table->integer('updated_by')->unsigned()->nullable()->comment('reference profile.reg_code of the system who modified  the application type');
            $table->timestamp('updated_at')->nullable()->comment('Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_travel_minor');
    }
}
