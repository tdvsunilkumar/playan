<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CtoBploAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_bplo_assessment', function (Blueprint $table) {
            $table->id();
            $table->string('assess_year')->default('0')->nullable();
            $table->integer('assess_month')->length(2)->default('0')->nullable();
            $table->integer('busn_id')->default('0')->comment('Ref-Table: bplo_business.id');
            $table->integer('app_code')->default(0)->comment('1-New, 2-Renew');
            $table->integer('payment_mode')->default('0');
            $table->integer('busn_psic_id')->default('0')->comment('Ref-Table: bplo_business_psic.id');
            $table->integer('subclass_id')->default('0')->comment('Ref-Table: psic_subclass.subclass_id');
            $table->integer('tfoc_id')->default('0')->comment('cto_tfoc.tfoc_id');
            $table->integer('agl_account_id')->default('0')->comment('Ref-Table: acctg_account_general_ledger.id');
            $table->integer('sl_id')->default('0')->comment('Ref-Table: acctg_account_subsidiary_ledger.sl_id');
            $table->double('tfoc_amount',14,2)->nullable();
            $table->double('tfoc_tmp_amount',14,2)->nullable();
            $table->integer('assess_is_surcharge')->default('0')->length(1)->nullable();
            $table->integer('assess_is_interest')->default('0')->length(1)->nullable();
            $table->integer('surcharge_gl_id')->default('0')->comment('Ref-Table: acctg_account_general_ledger.id')->nullable();
            $table->integer('surcharge_sl_id')->default('0')->comment('Ref-Table: acctg_account_subsidiary_ledger.sl_id')->nullable();
            $table->double('surcharge_fee',14,2)->nullable();
            $table->integer('interest_gl_id')->default('0')->comment('Ref-Table: acctg_account_general_ledger.id')->nullable();
            $table->integer('interest_sl_id')->default('0')->comment('Ref-Table: acctg_account_subsidiary_ledger.sl_id')->nullable();
            $table->double('interest_fee',14,2)->nullable();
            
            $table->integer('surcharge_rate',14,2)->default('0');
            $table->integer('surcharge_rate_type')->length(1)->default('0')->comment('1 - Percentage, 2 - Fixed Amount');
            $table->integer('interest_rate',14,2)->default('0');
            $table->integer('interest_rate_type')->length(1)->default('0')->comment('1 - Percentage, 2 - Fixed Amount');

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
        Schema::dropIfExists('cto_bplo_assessment');
    }
}
