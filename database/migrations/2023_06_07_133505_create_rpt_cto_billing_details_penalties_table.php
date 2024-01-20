<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoBillingDetailsPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cto_billing_details_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cb_code');
            $table->unsignedBigInteger('rp_property_code');
            $table->unsignedBigInteger('rp_code');
            $table->integer('cb_control_year');
            $table->integer('cbd_covered_year');
            $table->integer('sd_mode');
            $table->unsignedBigInteger('rpo_code');
            $table->decimal('cbd_assessed_value',20,3);
            $table->unsignedBigInteger('trevs_id')->comment('Ref-Table: rpt_cto_tax_revenues.id');
            $table->integer('tax_revenue_year')->comment('1=Upcoming Year, 2=Current Year, 3= Previous Years');
            $table->unsignedBigInteger('basic_penalty_tfoc_id')->comment('Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('basic_penalty_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('basic_penalty_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('basic_penalty_rate',5,3);
            $table->decimal('basic_penalty_amount',20,3);
            $table->unsignedBigInteger('sef_penalty_tfoc_id')->comment('SEF Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sef_penalty_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sef_penalty_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('sef_penalty_rate',5,3);
            $table->decimal('sef_penalty_amount',20,3);
            $table->unsignedBigInteger('sh_penalty_tfoc_id')->comment('SH Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sh_penalty_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sh_penalty_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('sh_penalty_rate',5,3);
            $table->decimal('sh_penalty_amount',20,3);
            $table->integer('cbd_is_paid')->comment('0=if the billing is not paid,1=if the billing already paid');
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
        Schema::dropIfExists('rpt_cto_billing_details_penalties');
    }
}
