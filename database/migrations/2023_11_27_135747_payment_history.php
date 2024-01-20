<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaymentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id')->unsigned();//
            $table->integer('busn_id')->unsigned()->nullable();
            $table->integer('rp_property_code')->unsigned()->nullable();
            $table->integer('rp_code')->unsigned()->nullable();
            $table->integer('client_id')->unsigned();//
            $table->integer('bill_year')->unsigned();//
            $table->integer('bill_month')->unsigned();//
            $table->date('bill_due_date');//
            $table->integer('app_code')->unsigned();//
            $table->integer('pm_id')->unsigned();
            $table->integer('pap_id')->unsigned();
            $table->string('particulars', 255)->nullable();
            $table->decimal('total_amount',10,2);
            $table->decimal('total_paid_amount',10,2);
            $table->string('or_no', 255)->nullable();
            $table->date('or_date')->nullable();
            $table->string('transaction_no', 255)->nullable();
            $table->string('attachement', 255)->nullable();
            $table->string('payment_status', 255);
            $table->date('payment_date');
            $table->boolean('is_synced')->default(0);
            $table->string('payment_taransaction_id', 255)->nullable();
            $table->string('merchant', 255)->nullable();
            $table->string('txnstatus', 255)->nullable();
            $table->string('paymentid', 255);
            $table->string('transactiontype', 255)->nullable();
            $table->string('authcode', 255)->nullable();
            $table->string('txnmessage', 255)->nullable();
            $table->string('token', 255)->nullable();
            $table->string('tokentype', 255)->nullable();
            $table->string('cardholder', 255)->nullable();
            $table->string('issuingbank', 255)->nullable();
            $table->string('cardnomask', 255)->nullable();
            $table->string('cardexp', 255)->nullable();
            $table->string('cardtype', 255)->nullable();
            $table->integer('settletaid')->unsigned();
            $table->integer('tid')->unsigned()->nullable();
            $table->string('currencycode', 255)->nullable();
            $table->string('payment_response', 255)->nullable();
            $table->integer('is_approved')->unsigned()->default(0)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_history');
    }
}
