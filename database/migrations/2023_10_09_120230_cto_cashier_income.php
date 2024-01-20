<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CtoCashierIncome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_cashier_income', function (Blueprint $table) {
            $table->id();
            $table->integer('cashier_id')->comment('Raf-Table: cto_cashier.id');
            $table->integer('cashier_details_id')->comment('Raf-Table: cto_cashier_details.id');
            $table->integer('tfoc_is_applicable')->comment('Make a default in saving of details in the system. 1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous');
            $table->string('taxpayer_name')->nullable(); 
            $table->integer('tfoc_id')->comment('Ref-Table: cto_tfocs.id')->default(0);
            $table->integer('fund_id')->comment('Ref-Table: cto_tfocs.fund_id')->default(0);
            $table->integer('gl_account_id')->comment('Ref-Table:cto_tfocs.gl_account_id');
            $table->integer('sl_account_id')->comment('Ref-Table:cto_tfocs.sl_id');
            $table->date('cashier_or_date')->nullable();
            $table->string('or_no')->comment('This is a duplicate record from the mother table:cto_cashier.or_no')->default(0);
            $table->double('amount',14,3)->comment('Amount');
            $table->string('form_code')->comment('Ref-Table: cto_payment_or_assignments.id')->nullable();
            $table->integer('or_register_id')->comment('Ref-Table: cto_payment_or_registers.id')->default(0);
            $table->integer('or_from')->comment('Ref-Table: cto_payment_or_assignments.from')->nullable();
            $table->integer('or_to')->comment('Ref-Table: cto_payment_or_assignments.to')->nullable();
            $table->integer('coa_no')->comment('Ref-Table: cto_payment_or_registers.coa_no')->default(0);
            $table->integer('is_collected')->default('0');
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
        Schema::dropIfExists('cto_cashier_income');
    }
}
