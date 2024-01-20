<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CtobploFinalAssessmentDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_bplo_final_assessment_details', function (Blueprint $table) {
            $table->id();
            $table->string('assess_year')->default('0')->nullable();
            $table->date('assess_due_date')->nullable();
            $table->integer('busn_id')->default('0')->comment('Ref-Table: bplo_business.id');
            $table->text('assesment_ids')->comment('cto_bplo_assessment.id')->nullable();
            $table->text('assessment_details_ids')->comment('cto_bplo_assessment_details.id')->nullable();;
            $table->double('sub_amount',14,2)->nullable();
            $table->double('surcharge_fee',14,2)->nullable();
            $table->double('interest_fee',14,2)->nullable();

            $table->integer('surcharge_rate',14,2)->default('0');
            $table->integer('surcharge_rate_type')->length(1)->default('0')->comment('1 - Percentage, 2 - Fixed Amount');
            $table->integer('interest_rate',14,2)->default('0');
            $table->integer('interest_rate_type')->length(1)->default('0')->comment('1 - Percentage, 2 - Fixed Amount');

            $table->double('total_surcharge_interest_fee',14,2)->nullable();
            $table->double('total_amount',14,2)->nullable();
            $table->integer('payment_mode')->default('0');
            $table->integer('app_code')->default(0)->comment('1-New, 2-Renew');
            $table->integer('assessment_period')->default('0')->comment("'1' => ['1'=>'Annual'],'2' => ['1'=>'1 st Semester','2'=>'2 st Semester'],'3' => ['1'=>'1 st Quarter','2'=>'2 st Quarter','3'=>'3 st Quarter','4'=>'4 st Quarter']");
            $table->integer('payment_status')->default('0')->length(1)->nullable();
            $table->integer('cashier_id')->default('0')->comment('Raf-Table: cto_cashier.id');
            $table->date('payment_date')->nullable();
            $table->string('top_transaction_no')->default('0')->comment('Table Ref- cto_top_transactions.transaction_no ')->nullable();
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
        Schema::dropIfExists('cto_bplo_final_assessment_details');
    }
}
