<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusinessDelinquents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_delinquents', function (Blueprint $table) {
            $table->id();
            $table->string('year')->default('0')->nullable();
            $table->integer('busn_id')->default('0')->comment('Ref-Table: bplo_business.id');
            $table->integer('retire_id')->default('0')->comment('Ref-Table: bplo_business_retirement.id');
            $table->text('final_assessment_ids')->comment('Comma separated ids Ref-Table: cto_bplo_final_assessment_details.id')->nullable();
            $table->double('sub_amount',14,2)->nullable();
            $table->double('surcharge_fee',14,2)->nullable();
            $table->double('interest_fee',14,2)->nullable();
            $table->double('total_amount',14,2)->nullable();
            $table->integer('app_code')->default(0)->comment('1-New, 2-Renew, 3-Retire');
            $table->integer('payment_status')->default('0')->length(1)->nullable();
            $table->string('transaction_no')->default('0')->comment('Raf-Table: cto_top_transactions.transaction_no');
            $table->date('payment_date')->nullable();
            $table->integer('is_approved')->default('0')->comment('This flag will update from user through email');
            $table->datetime('acknowledged_date')->nullable()->comment('This date will update from user through email');
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
        Schema::dropIfExists('bplo_business_delinquents');
    }
}
