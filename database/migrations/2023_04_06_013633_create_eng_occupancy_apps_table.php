<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngOccupancyAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_occupancy_apps', function (Blueprint $table) {
            $table->id();
            $table->string('ebpa_id')->comment('ref-Table: eng_bldg_permit_app.ebpa_id (ebpa_permit_no)');
            $table->string('eoa_application_no')->comment('Application No.');
            $table->integer('eoa_application_type')->comment('Partial, Full');
            $table->date('dateissued')->comment('Date Issued');
            $table->integer('client_id')->comment('Owner Name ref-Table:clients.id');
            $table->date('eoa_date_paid')->comment('Paid date');
            $table->string('p_mobile_no')->comment('Mobile Number');
            $table->string('rpo_address_house_lot_no')->comment('House / Lot No.');
            $table->string('rpo_address_street_name')->comment('Street Name');
            $table->string('rpo_address_subdivision')->comment('Subdivision');
            $table->integer('brgy_code');
            $table->integer('top_transaction_type_id')->comment('Ref-Table: cto_top_transaction_type.id');
            $table->integer('tfoc_id')->comment('ref-Table: eng_service.tfoc_id');
            $table->integer('agl_account_id')->comment('ref-Table: cto_tfoc.agl_account_id');
            $table->integer('sl_id')->comment('ref-Table: cto_tfoc.sl_id');
            $table->string('eoa_building_structure');
            $table->string('nameofproject');
            $table->integer('ebpa_location')->comment('ref-Table:eng_bldg_permit_app.ebpa_location');
            $table->integer('ebot_id')->comment('ref-Table:eng_bldg_permit_app.ebot_id');
            $table->integer('ebfd_no_of_storey')->comment('ref-Table:eng_bldg_fees_details.ebfd_no_of_storey');
            $table->integer('no_of_units')->comment('ref-Table:eng_bldg_permit_app.no_of_units');
            $table->integer('ebfd_floor_area')->comment('ref-Table:eng_bldg_fees_details.ebfd_floor_area');
            $table->date('eoa_date_of_completion');
            $table->integer('eoa_floor_area')->comment('Floor Area Dimension ref-Table: eng_bldg_fees_details.ebfd_floor_area');
            $table->integer('eoa_firstfloorarea')->comment('1st Floor');
            $table->integer('eoa_secondfloorarea')->comment('2nd Floor');
            $table->integer('eoa_lotarea')->comment('Lot Area - sq. mtr.');
            $table->integer('eoa_perimeter')->comment('Perimeter - l.mtr');
            $table->double('eoa_projectcost',8,2)->comment('Project Cost');
            $table->integer('cashier_id')->comment('Ref-Table: cto_cashier.id');
            $table->double('eoa_surcharge_fee',8,2)->comment('Surcharge Fee');
            $table->integer('surcharge_tfoc_id')->comment('Ref-Table: cto_tfocs.tfoc_id');
            $table->integer('surcharge_gl_id')->comment('Ref-Table: cto_tfocs.tfoc_surcharge_gl_id');
            $table->integer('surcharge_sl_id')->comment('Ref-Table: acctg_account_subsidiary_ledger.sl_id');
            $table->double('eoa_total_net_amount',8,2)->comment('sum of fees exclusive of surcharge');
            $table->double('eoa_total_fees',8,2)->comment('Total Fee');
            $table->integer('eoa_opd_created_by')->default('0');
            $table->integer('eoa_opd_approved_by')->default('0');
            $table->integer('eoa_opd_modified_by')->default('0');
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
        Schema::dropIfExists('eng_occupancy_apps');
    }
}
