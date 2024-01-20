<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoCashierDetailsEngOccupancyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_cashier_details_eng_occupancy', function (Blueprint $table) {
            $table->id();
            $table->integer('cashier_year')->comment('Current Year');
            $table->integer('cashier_month')->comment('month');
            $table->integer('cashier_id')->comment('Ref-Table: cto_cashier.id')->default(0);
            $table->integer('cashierd_id')->comment('Ref-Table: cto_cashier_detail.id')->default(0); 
            $table->integer('top_transaction_id')->comment('Ref-Table: cto_top_transactions.id')->default(0);
            $table->integer('tfoc_is_applicable')->comment('Make a default in saving of details in the system. 1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous');
            $table->integer('tcoc_id')->comment('Ref-Table:eng_job_request.tfoc_id')->default(0);
            $table->integer('agl_account_id')->comment('ref-Table:eng_job_request.agl_account_id')->default(0);
            $table->integer('sl_id')->comment('ref-Table:eng_job_request.sl_id')->default(0);
            $table->string('fees_description')->comment('Fee Description');
            $table->string('tfc_amount')->comment('Amount');
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
        Schema::dropIfExists('cto_cashier_details_eng_occupancy');
    }
}
