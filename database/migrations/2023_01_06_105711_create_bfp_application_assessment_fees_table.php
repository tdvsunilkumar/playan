<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpApplicationAssessmentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bfp_application_assessment_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id');
            $table->integer('bfpas_id')->comment('foreign key foreign key bfp_application_assessment.id');
            $table->integer('bff_id')->comment('Ref-Table: bfp_application_form.id'); 
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.busn_id');
            $table->integer('fmaster_id')->comment('Ref-Table: bfp_fees_master.id');
            $table->text('fee_option_json')->nullable()->comment('Json');
            $table->double('baaf_assessed_amount', 8, 2)->comment('Base of Computation');
            $table->double('baaf_amount_fee', 8, 2)->comment('Amount Fee');
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
        Schema::dropIfExists('bfp_application_assessment_fees');
    }
}
