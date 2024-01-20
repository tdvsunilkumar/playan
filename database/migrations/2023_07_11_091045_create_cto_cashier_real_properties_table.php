<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoCashierRealPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_cashier_real_properties', function (Blueprint $table) {
            $table->id();
            $table->integer('cashier_year')->nullable();
            $table->integer('cashier_month')->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->unsignedBigInteger('top_transaction_id')->nullable();
            $table->integer('tfoc_is_applicable')->nullable();
            $table->string('or_no',100)->nullable();
            $table->unsignedBigInteger('cb_code')->nullable();
            $table->unsignedBigInteger('rp_code')->nullable();
            $table->unsignedBigInteger('rp_property_code')->nullable();
            $table->string('pk_code',2)->nullable();
            $table->string('rp_tax_declaration_no',20)->nullable();
            $table->tinyInteger('cb_billing_mode')->nullable()->comment('0=for Single Property Billing,1=for Multiplie Property Billing');
            $table->unsignedBigInteger('cb_control_no')->nullable();
            $table->string('transaction_no',250)->nullable();
            $table->unsignedBigInteger('tcm_id')->nullable()->comment('when tax credit amount>0 Ref-Table: cto_tax_credit_management.id');
            $table->unsignedBigInteger('tax_credit_gl_id')->nullable()->comment('when tax credit amount>0');
            $table->unsignedBigInteger('tax_credit_sl_id')->nullable()->comment('when tax credit amount>0');
            $table->decimal('tax_credit_amount',20,3)->nullable();
            $table->tinyInteger('tax_credit_is_useup')->default('0')->comment('1 = Used, 0 = Not Used');
            $table->unsignedBigInteger('previous_cashier_id')->nullable()->comment('Credit amount applied cashier id');
            $table->tinyInteger('is_short_collection')->default('0')->comment('1 = Yes, 0 = No');
            $table->decimal('basic_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');
            $table->decimal('basic_discount_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');
            $table->decimal('basic_penalty_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');

            $table->decimal('sef_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');
            $table->decimal('sef_discount_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');
            $table->decimal('sef_penalty_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');

            $table->decimal('sh_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');
            $table->decimal('sh_discount_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');
            $table->decimal('sh_penalty_amount',20,3)->nullable()->comment('New value after genearal revision to calculate short collection');
            $table->tinyInteger('is_short_collection_paid')->default('0')->comment('1 = Yes, 0 = No');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('cto_cashier_real_properties');
    }
}
