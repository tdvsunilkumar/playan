<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code');
            $table->unsignedBigInteger('rp_property_code');
            $table->string('pk_code',1);
            $table->integer('rp_app_appraised_by');
            $table->date('rp_app_appraised_date');
            $table->integer('rp_app_appraised_is_signed')->comment("1=signed,0=not signed");

            $table->integer('rp_app_assessed_by');
            $table->date('rp_app_assessed_date');

            $table->integer('rp_app_recommend_by');
            $table->date('rp_app_recommend_date');
            $table->integer('rp_app_recommend_is_signed')->comment("1=signed,0=not signed");

            $table->integer('rp_app_approved_by');
            $table->date('rp_app_approved_date');
            $table->integer('rp_app_approved_is_signed')->comment("1=signed,0=not signed");
            $table->integer('rp_app_cancel_is_direct')->comment("1=Direct Cancellation, 0=Not direct cancellation");
            $table->integer('rp_app_cancel_by')->nullable();
            $table->integer('rp_app_cancel_type')->nullable();
            $table->date('rp_app_cancel_date')->nullable();
            $table->string('rp_app_cancel_by_td_no',50)->nullable();
            $table->string('rp_app_cancel_remarks',100)->nullable();
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
        Schema::dropIfExists('rpt_property_approvals');
    }
}
