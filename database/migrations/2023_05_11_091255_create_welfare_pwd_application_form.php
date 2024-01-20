<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfarePwdApplicationForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_pwd_application_form', function (Blueprint $table) {
            $table->id();
            $table->integer('cit_id')->comment('ref-Table: citizens.cit_id');
            $table->boolean('wpaf_application_type')->comment('New Applicant, Renewal');
            $table->string('wpaf_pwd_id_number')->nullable()->comment('FORMAT(RR-PPMM-BBB-NNNNNNN) R=Region P=Province M=Municipal B=Barangay N= Series No(Incremental)');
            $table->date('wpaf_date_applied')->nullable();
            $table->integer('wptod_id')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->boolean('pwd_cause_type')->nullable()->comment('Congenital / Inborn, Acquired');
            $table->integer('wpcodi_id')->nullable()->comment('ref-Table: welfare_pwd_cause_of_disability_inborn');
            $table->integer('wpcoda_id')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wpaf_brgy_id')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wpaf_municipal')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wpaf_province')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wpaf_region')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wpsoe_id')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wpcoe_id')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wptoe_id')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->integer('wptoo_id')->nullable()->comment('ref-Table: welfare_pwd_type_of_disability');
            $table->string('wpaf_sss')->nullable();
            $table->string('wpaf_gsis')->nullable();
            $table->string('wpaf_pagibig')->nullable();
            $table->string('wpaf_psn')->nullable();
            $table->string('wpaf_philhealth')->nullable();
            $table->integer('wpaf_fathersname')->nullable()->comment('ref-Table: citizens.cit_id');
            $table->integer('wpaf_mothersname')->nullable()->comment('ref-Table: citizens.cit_id');
            $table->integer('wpaf_guardiansname')->nullable()->comment('ref-Table: citizens.cit_id');
            $table->string('wpaf_accomplished_type')->nullable();
            $table->string('wpaf_accomplished_by')->nullable();
            $table->string('wpaf_physician')->nullable();
            $table->string('wpaf_physician_license')->nullable();
            $table->integer('wpaf_processing_officer')->nullable()->comment('ref-Table: hr_employees.id');
            $table->integer('wpaf_approving_officer')->nullable()->comment('ref-Table: hr_employees.id');
            $table->integer('wpaf_encoder')->nullable()->comment('ref-Table: hr_employees.id');
            $table->string('wpaf_reporting_unit')->nullable();
            $table->string('wpaf_control_no')->nullable();
            $table->integer('wpaf_is_active')->length(1)->comment('if the status of the application type is active or not');
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
        Schema::dropIfExists('welfare_pwd_application_form');
    }
}
