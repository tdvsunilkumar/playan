<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertiesAssessmentNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_properties_assessment_notices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code')->nullable()->comment('Ref-Table: rpt_properties.id');
            $table->unsignedBigInteger('rp_property_code')->nullable()->comment('Ref-Table: rpt_properties.rp_property_code');
            $table->string('ntob_year',4)->nullable()->comment('Current Year');
            $table->string('ntob_month',2)->nullable()->comment('Current Month');
            $table->string('ntob_control_no',50)->nullable()->comment('Combination of (ntob_year-ntob_month+ntob_no) like "2020-120000');
            $table->unsignedBigInteger('rp_registered_by')->nullable()->comment('reference profile.p_code of the system who registered the rpt_property');
            $table->unsignedBigInteger('rp_modified_by')->nullable()->comment('reference profile.p_code of the system  who update the rpt_property');
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
        Schema::dropIfExists('rpt_properties_assessment_notices');
    }
}
