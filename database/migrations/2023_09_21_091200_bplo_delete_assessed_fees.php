<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploDeleteAssessedFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_deleted_assessed_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->default(0)->comment('Ref-Table: bplo_business.id');
            $table->integer('year')->comment('Current Year');
            $table->integer('month')->comment('month');
            $table->integer('tfoc_id')->default(0)->comment('Ref-Table:cto_bplo_assessment.tfoc_id');
            $table->integer('app_code')->default(0)->comment('Application Type');
            $table->integer('pm_id')->default(0)->comment('Payment Mode');
            $table->integer('pap_id')->default(0)->comment('Assessment Period');
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
        Schema::dropIfExists('bplo_deleted_assessed_fees');  
    }
}
