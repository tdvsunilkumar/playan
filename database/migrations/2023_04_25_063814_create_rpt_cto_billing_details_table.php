<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoBillingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cto_billing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cb_code');
            $table->unsignedBigInteger('rp_property_code');
            $table->unsignedBigInteger('rp_code');
            $table->integer('cb_control_year');
            $table->integer('cbd_covered_year');
            $table->integer('sd_mode');
            $table->unsignedBigInteger('rpo_code');
            $table->decimal('cbd_assessed_value',20,3);
            $table->unsignedBigInteger('trevs_id');
            $table->integer('tax_revenue_year')->comment('1=Upcoming Year, 2=Current Year, 3= Previous Years');
            $table->unsignedBigInteger('basic_tfoc_id')->comment('Basic: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('basic_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('basic_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('basic_amount',20,3);
            $table->decimal('sef_amount',20,3);
            $table->unsignedBigInteger('sef_tfoc_id')->comment('SEF: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sef_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sef_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('sh_amount',20,3);
            $table->unsignedBigInteger('sh_tfoc_id')->comment('SEF: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sh_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sh_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
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
        Schema::dropIfExists('rpt_cto_billing_details');
    }
}
