<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoApplicationFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_application_forms', function (Blueprint $table) {
            $table->id();
            $table->string('caf_control_no')->comment('Control No System generated');
            $table->date('caf_date')->comment('Format: [yyyy-mm-dd]. User Audit date of saving the info');
            $table->integer('client_id')->comment('ref-Table:client.client_id');
            $table->string('caf_name_firm')->comment('Complete Name & Address of Firm (if Applicant is Business Firm)')->nullable();
            $table->integer('caf_client_representative_id')->comment('Authorized Representative')->nullable();
            $table->string('client_telephone')->comment('Telephone')->nullable();
            $table->integer('top_transaction_type_id')->comment('Ref-Table: cto_top_transaction_type.id');
            $table->integer('tfoc_id')->comment('ref-Table: cto-tfoc.tfoc_id');
            $table->integer('cm_id')->comment('ref-Table: cpdo_module.id');
            $table->integer('agl_account_id')->comment('ref-Table: cto_tfoc.agl_account_id');
            $table->integer('sl_id')->comment('ref-Table: cto_tfoc.sl_id');
            $table->double('caf_amount',8,2)->comment('Amount');
            $table->integer('cs_id')->comment('ref-Table : cpdo_status.id default = 1');
            $table->integer('csd_id')->comment('ref-Table : cpdo_status_details.csd_id default = 1');
            $table->integer('cna_id')->comment('ref-Table: cpdo_nature_app.can_id');
            $table->string('caf_purpose_application')->comment('Purpose  of Application');
            $table->string('caf_type_project')->comment('Type of Project');
            $table->string('caf_complete_address')->comment('Complete Address')->nullable();
            $table->integer('cpt_id')->comment('ref-Table:cpdo_project_tenure.cpt_id')->nullable();
            $table->string('cpt_others')->comment('if Project Tenure is Temporary Cpt_others required to input a number of years')->nullable();
            $table->string('caf_site_area')->comment('Project Site Area')->nullable();
            $table->integer('croh_id')->comment('ref-Table: cpdo_right_over_hand.croh_id');
            $table->string('caf_radius')->comment('Project Site Area')->nullable();
            $table->string('caf_use_project_site')->comment('Use of Project Site')->nullable();
            $table->string('caf_product_manufactured')->comment('caf_product_manufactured')->nullable();
            $table->string('caf_averg_product_output')->comment('Average Production Output')->nullable();
            $table->string('caf_power_source')->comment('Source')->nullable();
            $table->string('caf_power_daily_consump')->comment('Daily consumption')->nullable();
            $table->string('caf_employment_current')->comment('Current')->nullable(); 
            $table->string('caf_employment_project')->comment('Projected')->nullable();
            $table->string('caf_others_nature_of_applicant')->comment('Others(Nature Of Applicant')->nullable(); 
            $table->string('caf_remarks')->comment('Remarks/Comments')->nullable();
            $table->integer('is_active')->default('0');
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
        Schema::dropIfExists('cpdo_application_forms');
    }
}
