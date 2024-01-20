<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoAccountsReceivableDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_accounts_receivable_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ar_id')->nullable()->comment('Ref-Table: cto_accounts_receivables.id');
            $table->unsignedBigInteger('top_transaction_id')->nullable()->comment('Ref-Table: cto_top_transactions.id');
            $table->tinyInteger('payee_type')->nullable()->comment('1=Client(table:clients), 2=Citizen(table:citizens)');
            $table->unsignedBigInteger('taxpayer_id')->nullable()->comment('Taxpayers and Citizen ID reference number');
            $table->string('taxpayer_name')->nullable()->comment('Taxpayer Name');
            $table->unsignedBigInteger('pcs_id')->nullable()->comment('Ref-Table: cto_payment_cashier_system.id');
            $table->unsignedBigInteger('rp_property_code')->nullable()->comment('Ref-Table: rp_properties.rp_property_code');
            $table->unsignedBigInteger('rp_code')->nullable()->comment('Ref-Table: rp_properties.id. the original rp_code');
            $table->integer('pk_id')->nullable()->comment('Ref-Table: rpt_property_kinds.id');
            $table->integer('ar_covered_year')->nullable();
            $table->integer('sd_mode')->nullable();
            $table->string('rp_app_effective_year',4)->nullable()->comment('Ref-Table: rpt_properties.rp_app_effectivity_year');
            $table->decimal('rp_assessed_value',20,3)->nullable()->comment('Total Assessed Values for both the Land, Building & Properties');
            $table->integer('rvy_revision_year_id')->nullable()->comment('Ref-Table: rpt_revision_year.id');
            $table->integer('brgy_code_id')->nullable()->comment('Ref-Table: barangays.id');
            $table->unsignedBigInteger('trevs_id')->nullable()->comment('Ref-Table: rpt_cto_billing_details.trevs_id');
            $table->integer('tax_revenue_year')->comment('1=Upcoming Year, 2=Current Year, 3= Previous Years');
            $table->unsignedBigInteger('rp_billing_id')->nullable()->comment('Ref-Table: rpt_cto_billing.id... always the current billing ID until its full paid');
            $table->integer('transaction_id')->nullable()->comment('Ref-Table: cto_top_transactions.id .. when the Tax Order Of Payment has been created by the City Treasurers Office.');
            $table->string('transaction_no')->nullable()->comment('Ref-Table: cto_top_transactions.transaction_no .. when the Tax Order Of Payment has been created by the City Treasurer Office.');
            $table->tinyInteger('cbd_is_paid')->default('0')->comment('if its paid or not');
            $table->unsignedBigInteger('basic_tfoc_id')->comment('Basic: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('basic_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('basic_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('basic_amount',20,3);

            $table->unsignedBigInteger('basic_discount_tfoc_id')->comment('Discount: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('basic_discount_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('basic_discount_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('basic_discount_amount',20,3);

            $table->unsignedBigInteger('basic_penalty_tfoc_id')->comment('Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('basic_penalty_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('basic_penalty_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('basic_penalty_amount',20,3);

            $table->decimal('sef_amount',20,3);
            $table->unsignedBigInteger('sef_tfoc_id')->comment('SEF: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sef_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sef_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');

            $table->unsignedBigInteger('sef_discount_tfoc_id')->comment('SEF Discount: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sef_discount_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sef_discount_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('sef_discount_amount',20,3);

            $table->unsignedBigInteger('sef_penalty_tfoc_id')->comment('SEF Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sef_penalty_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sef_penalty_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('sef_penalty_amount',20,3);

            $table->decimal('sh_amount',20,3);
            $table->unsignedBigInteger('sh_tfoc_id')->comment('SEF: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sh_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sh_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');

            $table->unsignedBigInteger('sh_discount_tfoc_id')->comment('SH Discount: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sh_discount_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sh_discount_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('sh_discount_amount',20,3);

            $table->unsignedBigInteger('sh_penalty_tfoc_id')->comment('SH Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id');
            $table->unsignedBigInteger('sh_penalty_gl_id')->comment('Ref-Table: cto_tfocs.agl_account_id');
            $table->unsignedBigInteger('sh_penalty_sl_id')->comment('Ref-Table: cto_tfocs.sl_id');
            $table->decimal('sh_penalty_amount',20,3);

            $table->integer('cashier_id')->nullable()->comment('Ref-Table: cto_cashier');
            $table->integer('ortype_id')->nullable()->comment('Ref-Table: cto_payment_or_types.id');
            $table->integer('or_assignment_id')->nullable()->comment('Ref-Table: cto_payment_or_assignments.id');
            $table->integer('or_register_id')->nullable()->comment('Ref-Table: cto_payment_or_registers.id');
            $table->integer('coa_no')->nullable()->comment('Ref-Table: cto_payment_or_registers.coa_no');
            $table->integer('or_no')->nullable()->comment('Ref-Table: cto_payment_or_registers.coa_no');
            $table->tinyInteger('status')->default('0')->comment('when the amounts(like rp_basic_amount, rp_sef_amount and rp_sht_amount) already set to ZERO');
            $table->integer('created_by')->nullable()->comment('reference hr_employee_id of the system who registered the details');
            $table->integer('updated_by')->nullable()->comment('reference hr_employee_id of the system who registered the details');
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
        Schema::dropIfExists('cto_accounts_receivable_details');
    }
}
