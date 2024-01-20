<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfareSwaDependent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_swa_dependent', function (Blueprint $table) {
            $table->id();
            $table->integer('wswa_id')->length(11)->comment('ref-Table: welfare_social_welfare_assistance.wswa_id');
            $table->integer('cit_id')->length(11)->comment('ref-Table: welfare_social_welfare_assistance.cit_id');
            $table->integer('wsd_cit')->length(11)->comment('ref-Table: citizens.cit_id');
            $table->string('wsd_relation');
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
        Schema::dropIfExists('welfare_swa_dependent');
    }
}
