<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesWelfareSeniorCitizen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_welfare_senior_citizen', function (Blueprint $table) {
            $table->id();
            $table->integer('wsca_id')->length(11)->comment('ref-Table: welfare_solo_parent_application.wspa_id');
            $table->integer('req_id')->length(11)->comment('ref-Table: constant.senior_requirements');
            $table->string('fwsc_name');
            $table->string('fwsc_type');
            $table->decimal('fwsc_size');
            $table->string('fwsc_path');
            $table->integer('fwsc_is_active')->length(1)->comment('if the status of the application type is active or not');
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
        Schema::dropIfExists('files_welfare_senior_citizen');
    }
}
