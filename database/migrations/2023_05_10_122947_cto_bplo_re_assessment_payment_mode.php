<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CtoBploReAssessmentPaymentMode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_bplo_re_assessment_payment_mode', function (Blueprint $table) {
            $table->id();
            $table->integer('pmode_policy')->default(0)->comment('1=[Re-Assess] procedure will re-calculate current and previous years. Previous [Mode of Payment] will CHANGE based on the current selection. 2=[Re-Assess] procedure will re-calculate current and previous years. Previous [Mode of Payment] will NOT CHANGE based on the current selection. 3=[Re-Assess] procedure will re-calculate current year. Previous [Mode of Payment] will CHANGE based on the current selection. 4=[Re-Assess] procedure will re-calculate current year. Previous [Mode of Payment] will NOT CHANGE based on the current selection.');
            $table->text('remark')->nullable();
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
       Schema::dropIfExists('cto_bplo_re_assessment_payment_mode');
    }
}
