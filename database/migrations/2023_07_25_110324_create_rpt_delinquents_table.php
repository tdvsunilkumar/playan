<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptDelinquentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_delinquents', function (Blueprint $table) {
            $table->id();
            $table->string('year',5)->nullable();
            $table->unsignedBigInteger('rp_code')->comment('Ref-Table: rpt_properties.id');
            $table->unsignedBigInteger('rp_property_code')->comment('Ref-Table: rpt_properties.rp_property_code');
            $table->unsignedBigInteger('cb_code')->comment('Ref-Table: rpt_cto_billing.id');
            $table->decimal('basic_amount',20,2)->nullable();
            $table->decimal('sef_amount',20,2)->nullable();
            $table->decimal('sh_amount',20,2)->nullable();
            $table->decimal('basic_penalty',20,2)->nullable();
            $table->decimal('sef_penalty',20,2)->nullable();
            $table->decimal('sh_penalty',20,2)->nullable();
            $table->decimal('total_amount',20,2)->nullable();
            $table->integer('payment_status')->default('0')->length(1)->nullable();
            $table->string('transaction_no')->default('0')->comment('Raf-Table: cto_top_transactions.transaction_no');
            $table->date('payment_date')->nullable();
            $table->integer('is_approved')->default('0')->comment('This flag will update from user through email');
            $table->datetime('acknowledged_date')->nullable()->comment('This date will update from user through email');
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
        Schema::dropIfExists('rpt_delinquents');
    }
}
