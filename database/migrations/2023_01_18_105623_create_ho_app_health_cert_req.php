<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoAppHealthCertReq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_app_health_cert_req', function (Blueprint $table) {
            $table->id();
            $table->integer('hahc_id');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id');
            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id');
            $table->integer('citizen_id')->comment('Ref-Table: citizens.id');
            $table->integer('hahcr_category')->comment('Like Immunization, X-Ray, Stool and Other Exam Required');
            $table->string('req_id')->comment('Ref-Table: requirements.id WHERE req_dept_health_office=1');
            $table->date('hahcr_exam_date')->comment('Format: [yyyy-mm-dd]. ');
            $table->string('hahcr_exam_result')->comment('Examination Result/Remarks indicated here');
            $table->string('hahcr_remarks')->comment('Remarks or Additional Instruction');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ho_app_health_cert_req');
    }
}
