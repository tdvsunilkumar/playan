<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CtoTopBplo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_top_bplo', function (Blueprint $table) {
            $table->id();
            $table->integer('top_year')->default('0');
            $table->integer('top_month')->default('0');
            $table->text('final_assessment_ids')->comment('Comma separated ids Ref-Table: cto_bplo_final_assessment_details.id')->nullable();
            $table->integer('top_transaction_type_id')->length(10)->default('0');
            $table->integer('busn_id')->default('0')->comment('Ref-Table: bplo_business.id');
            $table->integer('app_code')->length(1)->default(0)->comment('1-New, 2-Renew, 3-Retire');
            $table->integer('pm_id')->default('0')->comment('Payment Mode');
            $table->integer('pap_id')->default('0')->comment('Assessment Period');
            $table->integer('top_is_posted')->default('0')->comment('Finalize Assessment');
            $table->integer('created_by')->length(14)->default('0');
            $table->integer('updated_by')->length(14)->default('0');
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
        Schema::dropIfExists('cto_top_bplo');
    }
}
