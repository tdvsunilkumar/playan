<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoTopTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_top_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->length(255)->nullable()->comment('transaction number to be use by Cashier for processing of payment, must be visible in all order of payment');
            $table->integer('top_transaction_type_id')->nullable()->comment('ref-Table: top_transaction_type.id = 19 Check Assignment for the details');
            $table->string('transaction_ref_no')->comment('table.id reference');
            $table->integer('tfoc_is_applicable')->default(0)->comment('1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous');
            $table->integer('tfoc_id')->comment('Applicable to Engineering, Certificate of Occupancy, Zoning and City Health');
            $table->double('amount',8,2)->comment('Total Amount');
            $table->integer('is_paid')->default('0')->comment('0=Unpaid, 1=Paid, make default as 0');
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
        Schema::dropIfExists('cto_top_transactions');
    }
}
