<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPettyCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_petty_cash', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('voucher_id')->unsigned()->comment('acctg_vouchers');
            $table->text('payee_id')->nullable()->comment('cbo_payee');
            $table->string('control_no', 40);
            $table->text('particulars')->nullable();            
            $table->double('total_amount')->default(0);
            $table->string('status', 40)->default('draft');
            $table->date('disbursement_date')->nullable();
            $table->date('replenishment_date')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->timestamp('disbursement_approved_at')->nullable();
            $table->text('disbursement_approved_by')->nullable();
            $table->timestamp('disbursement_disapproved_at')->nullable();
            $table->integer('disbursement_disapproved_by')->unsigned()->nullable();
            $table->text('disbursement_disapproved_remarks')->nullable();
            $table->timestamp('replenishment_approved_at')->nullable();
            $table->text('replenishment_approved_by')->nullable();
            $table->timestamp('replenishment_disapproved_at')->nullable();
            $table->integer('replenishment_disapproved_by')->unsigned()->nullable();
            $table->text('replenishment_disapproved_remarks')->nullable();            
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cto_petty_cash');
    }
}
