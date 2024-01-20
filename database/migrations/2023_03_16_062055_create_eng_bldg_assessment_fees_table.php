<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBldgAssessmentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('eng_bldg_assessment_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('ebpa_id')->comment('ref-Table: eng_bldg_permit_app.ebpa_id');
            $table->double('ebaf_zoning_amount',14,3)->nullable();
            $table->string('ebaf_zoning_assessed_by',75)->nullable();
            $table->string('ebaf_zoning_or_no',75)->nullable();
            $table->date('ebaf_zoning_date_paid')->nullable();
            $table->double('ebaf_linegrade_amount',14,3)->nullable();
            $table->string('ebaf_linegrade_assessed_by',75)->nullable();
            $table->string('ebaf_linegrade_or_no',75)->nullable();
            $table->date('ebaf_linegrade_date_paid')->nullable();
            $table->double('ebaf_bldg_amount',14,3)->nullable();
            $table->string('ebaf_bldg_assessed_by',75)->nullable();
            $table->string('ebaf_bldg_or_no',75)->nullable();
            $table->date('ebaf_bldg_date_paid')->nullable();
            $table->double('ebaf_plum_amount',14,3)->nullable();
            $table->string('ebaf_plum_assessed_by',75)->nullable();
            $table->string('ebaf_plum_or_no',75)->nullable();
            $table->date('ebaf_plum_date_paid')->nullable();
            $table->double('ebaf_elec_amount',14,3)->nullable();
            $table->string('ebaf_elec_assessed_by',75)->nullable();
            $table->string('ebaf_elec_or_no',75)->nullable();
            $table->date('ebaf_elec_date_paid')->nullable();
            $table->double('ebaf_mech_amount',14,3)->nullable();
            $table->string('ebaf_mech_assessed_by',75)->nullable();
            $table->string('ebaf_mech_or_no',75)->nullable();
            $table->date('ebaf_mech_date_paid')->nullable();
            $table->double('ebaf_others_amount',14,3)->nullable();
            $table->string('ebaf_others_assessed_by',75)->nullable();
            $table->string('ebaf_others_or_no',75)->nullable();
            $table->date('ebaf_others_date_paid')->nullable();
            $table->double('ebaf_total_amount',14,3);
            $table->string('ebaf_total_assessed_by',75);
            $table->string('ebaf_total_or_no',75);
            $table->date('ebaf_total_date_paid');
            $table->integer('ebaf_is_active')->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('eng_bldg_assessment_fees');
    }
}
