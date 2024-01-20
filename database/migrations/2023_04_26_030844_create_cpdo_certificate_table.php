<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_certificate', function (Blueprint $table) {
            $table->id();
            $table->string('cc_applicant_no')->comment('System generate the applicant no.');
            $table->date('cc_date')->comment('date.');
            $table->string('cc_falc_no')->comment('combination of applicant no - month - year.');
            $table->integer('caf_id')->comment('ref-table : cpdo_application_form.caf_id');
            $table->string('cc_rol')->comment('Right Over Land.');
            $table->string('cc_boc')->comment('Basis for Clearance.');
            $table->string('cc_name_project')->comment('Name of Project');
            $table->string('cc_area')->comment('Area.');
            $table->string('cc_location')->comment('Location.');
            $table->string('cc_project_class')->comment('Project Classification.');
            $table->string('cc_site_classification')->comment('Site Classification.');
            $table->string('cc_dominant')->comment('Dominant land….');
            $table->string('cc_evaluation')->comment('Evaluation of Facts.');
            $table->string('cc_decision')->comment('Decision.');
            $table->integer('cc_recom_approval')->comment('ref-table: hr_employee.id who will be first approval recommending Approval.');
            $table->string('cc_recom_approval_position')->commnet('Recommend Position');
            $table->date('cc_recom_approval_date')->comment('date recommended.');
            $table->integer('cc_noted')->comment('ref-table: hr_employee.id who will be first approval recommending Approval.');
            $table->string('cc_noted_position')->commnet('Noted Position');
            $table->date('cc_noted_date')->comment('date noted.');
            $table->integer('cc_approved')->comment('ref-table: hr_employee.id who will be first approval recommending Approval.');
            $table->string('cc_approved_position')->commnet('Approval Position');
            $table->date('cc_approved_date')->comment('date approved.');
            $table->string('cir_created_position')->commnet('Created Position');
            $table->integer('cc_notes_status')->default('0');
            $table->integer('cc_recom_status')->default('0');
            $table->integer('cc_approval_status')->default('0');
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
        Schema::dropIfExists('cpdo_certificate');
    }
}
