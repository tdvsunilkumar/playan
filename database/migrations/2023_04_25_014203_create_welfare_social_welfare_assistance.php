<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfareSocialWelfareAssistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_social_welfare_assistance', function (Blueprint $table) {
            $table->id();
            $table->integer('cit_id')->comment('ref-Table: citizens.cit_id');
            $table->integer('wsat_id')->comment('ref-Table: welfare_swa_assistance_type.wsat_id');
            $table->integer('wswa_amount');
            $table->date('wswa_date_applied');
            $table->integer('wsst_id')->nullable()->comment('ref-Table: welfare_swa_status_type.wsst_id');
            $table->integer('head_cit_id')->comment('ref-Table: citizens.cit_id')->nullable();
            $table->string('wswa_remarks')->nullable();
            $table->string('wswa_social_worker');
            $table->string('wswa_approved_by');
            $table->integer('created_by')->unsigned()->comment('reference profile.reg_code of the system who create the application type');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Format: [yyyy-mm-dd hh:mm:ss]. ');
            $table->integer('modified_by')->unsigned()->nullable()->comment('reference profile.reg_code of the system who modified  the application type');
            $table->timestamp('modified_date')->nullable()->comment('Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_social_welfare_assistance');
    }
}
