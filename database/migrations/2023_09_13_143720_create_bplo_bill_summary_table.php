<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBillSummaryTable extends Migration
{
    public function up()
    {
        Schema::create('bplo_bill_summary', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->default(0)->comment('Ref-Table: bplo_business.id');
            $table->integer('client_id')->default(0)->comment('Ref-Table: clients.id');
            $table->integer('bill_year')->comment('Current Year');
            $table->integer('bill_month')->comment('month');
            $table->date('bill_due_date')->nullable();
            $table->integer('app_code')->default(0)->comment('Application Type');
            $table->integer('pm_id')->default(0)->comment('Payment Mode');
            $table->integer('pap_id')->default(0)->comment('Assessment Period');
            $table->string('particulars', 255)->comment('Summary of the Payment');
            $table->double('total_amount', 14, 2)->default(0.00);
            $table->double('total_paid_amount', 14, 2)->default(0.00);
            $table->string('or_no', 255)->default('0')->comment('OR Number');
            $table->date('or_date')->nullable()->comment('OR Date');
            $table->string('transaction_no', 255)->nullable()->comment('Ref-Table: cto_top_transactions.transaction_no');
            $table->text('attachement')->nullable()->comment('Bill Receipt');
            $table->integer('payment_status')->default(0)->comment('Paid/Pending/Cancelled');
            $table->date('payment_date')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bplo_bill_summary');
    }
}