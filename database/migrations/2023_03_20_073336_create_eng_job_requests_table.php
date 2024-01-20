<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngJobRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_job_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ejr_jobrequest_no')->comment('FORMAT (CURRENT YEAR - SERIES) 2023-000001')->nullable();
            $table->integer('client_id')->comment('Owner');
            $table->string('Applicationtype')->comment('app type');
            $table->date('ejr_date_paid')->nullable();
            $table->string('rpo_address_house_lot_no')->comment('House / Lot No ref-Table:clients.rpo_address_house_lot_no');
            $table->string('rpo_address_street_name')->comment('Street Name No ref-Table:clients.p_address_street_name');
            $table->string('rpo_address_subdivision')->comment('Subdivision ref-Table:clients.p_address_subdivision');
            $table->integer('brgy_code')->comment('brgy_code ref-Table:clients.brgy_code');
            $table->string('p_mobile_no')->comment('ref-Table:clients.p_mobile_no');
            $table->integer('es_id')->comment('ref-Table: eng_service.es_id');
            $table->integer('top_transaction_type_id')->comment('Ref-Table: cto_top_transaction_type.id');
            $table->string('tfoc_id')->comment('ref-Table: eng_service.es_id');  
            $table->integer('agl_account_id')->comment('ref-Table: cto_tfoc.agl_account_id');
            $table->integer('sl_id')->comment('ref-Table: cto_tfoc.sl_id');
            $table->string('application_no')->comment('application number');
            $table->string('ejr_project_name')->comment('Name of Project');
            $table->integer('ebfd_floor_area')->comment('Floor Area-Dimension ref-Table: eng_bldg_fees_details.ebfd_floor_area');
            $table->integer('ejr_firstfloorarea')->comment('1 st Floor ');
            $table->integer('ejr_secondfloorarea')->comment('2 nd Floor ');
            $table->integer('ejr_lotarea')->comment('Lot Area - sq. mtr.');
            $table->integer('ejr_perimeter')->comment('Perimeter - l.mtr');
            $table->double('ejr_projectcost',14,2)->comment('Project Cost');
            $table->integer('cashier_id')->comment('Ref-Table: cto_cashier.id')->default(0);
            $table->double('ejr_surcharge_fee',14,2)->comment('Total Fee');
            $table->integer('surcharge_tfoc_id')->comment('Ref-Table: cto_tfocs.id'); 
            $table->integer('surcharge_gl_id')->comment('Ref-Table: cto_tfocs.tfoc_surcharge_gl_id'); 
            $table->integer('surcharge_sl_id')->comment('Ref-Table: cto_tfocs.tfoc_surcharge_sl_id'); 
            $table->double('ejr_total_net_amount',14,2)->comment('sum of fees exclusive of surcharge'); 
            $table->double('ejr_totalfees',14,2)->comment('Total Fee');
            $table->integer('ejr_opd_created_by')->commnet('reference hr_employee.p_code of the system who submitted the order of payment details');
            $table->integer('ejr_opd_approved_by')->comment('reference hr_employee.p_code of the system who approved the order of payment details');
            $table->integer('ejr_opd_modified_by')->comment('reference hr_employee.p_code of the system who modified the order of payment details');
            $table->integer('is_active')->default('0');
            $table->integer('is_approve')->default('0');
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
        Schema::dropIfExists('eng_job_requests');
    }
}
