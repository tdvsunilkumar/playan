<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoAccountsReceivableSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_accounts_receivable_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('pk_id')->unsigned()->comment('Ref-Table: rpt_property_kinds.id');
            $table->integer('ars_category')->unsigned()->comment('1=basic tax, 2=special education tax, 3=socialize housing tax');
            $table->integer('ars_fund_id')->unsigned()->comment('Ref-Table: acctg_fund_codes.id');
            $table->integer('gl_id')->unsigned()->comment('Ref-Table: acctg_account_general_ledgers.id');
            $table->integer('sl_id')->unsigned()->comment('Ref-Table: acctg_account_subsidiary_ledgers.id');
            $table->string('ars_remarks', 100);
            $table->tinyInteger('status')->default('1')->comment('0=Cancelled, 1=Active');
            $table->integer('created_by')->unsigned()->comment('reference hr_employee_id of the system who registered the details');
            $table->integer('updated_by')->unsigned()->comment('reference hr_employee_id of the system who update the details');
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
        Schema::dropIfExists('cto_accounts_receivable_setups');
    }
}
