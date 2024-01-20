<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoTfocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_tfocs', function (Blueprint $table) {
            $table->id();
            $table->integer('fund_id')->unsigned()->comment('Fund Code Ref-Table: acctg_fund_codes.id');
            $table->integer('ctype_id')->unsigned()->comment('Type Of Charges ref-table:cto_charge_types.ctype_id');
            $table->string('tfoc_old_code')->comment('Code This is old treasurer_income_account.tia_account_code');
            $table->string('gl_account_id')->comment('Chart OF Account Ref-Table: acctg_account_general_ledger.agl_code');
            $table->integer('sl_id')->comment('Ref-Table: acctg_account_subsidiary_ledger.sl_id');
            $table->string('tfoc_short_name')->comment('Account Short Name');
            $table->double('tfoc_amount',8,2)->comment('Amount Limit/Initial Amount')->nullable();
            $table->integer('tfoc_is_applicable')->comment('1=Business Permit, 2=Real Property, 3=Engineering, 4=Burial, 5=Community Tax, 9=Miscellaneous');
            $table->integer('tfoc_usage_business_permit')->comment('Usage: Business Permit Checkbox');
            $table->integer('tfoc_divided_fee')->comment('Divided Fee Checkbox')->nullable();
            $table->integer('tfoc_iterated_fee')->comment('Iterated Fee Checkbox')->nullable();
            $table->integer('tfoc_common_fee')->comment('Common Fee Checkbox')->nullable();
            $table->integer('tfoc_interest_fee')->comment('Interest Fee Checkbox')->nullable();
            $table->integer('tfoc_surcharge_fee')->comment('Surcharge Fee Checkbox')->nullable();
            $table->integer('tfoc_fire_code_fee')->comment('Is included in Fire Safety Inspection Fee Checkbox')->nullable();
            $table->integer('tfoc_surcharge_interest_fee')->comment('Default Surcharge and Interest Checkbox')->nullable();
            $table->integer('total_of_sl_id')->comment('totalsl_id');    
            $table->integer('tfoc_usage_engineering')->comment('checkbox for engineering fee');
            $table->string('tfoc_remarks')->comment('Remarks')->nullable();
            $table->integer('tfoc_surcharge_sl_id')->comment('surcharge sl id'); 
            $table->integer('tfoc_interest_sl_id')->comment('interest slid'); 
            $table->integer('tfoc_interest_gl_id')->comment('interest glid'); 
            $table->integer('tfoc_surcharge_gl_id')->comment('surcharge glid'); 
            $table->integer('total_of_gl_id')->comment('total gl id'); 
            $table->integer('tfoc_status')->comment('Remarks')->nullable();
            $table->integer('tfoc_usage_real_property')->comment('real property usage');
            $table->integer('tfoc_eachlineof_bussiness')->comment('eachlinebussiness')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('cto_tfocs');
    }
}
