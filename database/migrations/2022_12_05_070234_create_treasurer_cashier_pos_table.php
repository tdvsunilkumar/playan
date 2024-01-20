<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreasurerCashierPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasurer_cashier_pos', function (Blueprint $table) {
            $table->id();
            $table->string('ba_cover_year');
            $table->string('ba_business_account_no');
            $table->string('order_number');
            $table->double('totalamt_due', 8, 2);
            $table->integer('bas_id')->comment('foreign key bplo_assessment id');
            $table->double('totaltax_due', 8, 2);
            $table->double('surcharge', 8, 2);
            $table->double('interest', 8, 2);
            $table->double('subtotal', 8, 2);
            $table->double('otherdeduction', 8, 2);
            $table->double('appliedtax_credit', 8, 2);
            $table->double('nettax_due', 8, 2);
            $table->double('checkamount_paid', 8, 2);
            $table->double('cashamount_paid', 8, 2);
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
        Schema::dropIfExists('treasurer_cashier_pos');
    }
}
