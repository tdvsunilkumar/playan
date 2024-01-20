<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoTaxCreditManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_tax_credit_management', function (Blueprint $table) {
            $table->id();
			$table->integer('fund_id')->length(11)->default(0)->comment('Ref-Table: acctg_fund_codes.id');
			$table->integer('ctype_id')->length(11)->default(0)->comment('ref-table:cto_charge_types.ctype_id');
			$table->integer('tcm_gl_id')->length(11)->comment('Ref-Table: acctg_account_general_ledgers.id');
			$table->integer('tcm_sl_id')->length(11)->comment('Ref-Table: acctg_account_subsidiary_ledgers.id');
			$table->integer('pcs_id')->length(11)->default(1)->comment('Ref-Table: cto_payment_cashier_system.id');
			$table->string('tcm_remarks')->length(150)->comment('Remarks');
			$table->integer('tcm_status')->length(1)->comment('1=Active, 0=InActive');
			$table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
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
        Schema::dropIfExists('cto_tax_credit_management');
    }
}
