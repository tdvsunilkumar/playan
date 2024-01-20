<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code')->nullable();
            $table->string('pk_code',1)->nullable();
            $table->tinyInteger('rpss_mciaa_property');
            $table->tinyInteger('rpss_peza_property');
            $table->tinyInteger('rpss_beneficial_use');
            $table->unsignedBigInteger('rpss_beneficial_user_code')->nullable();
            $table->string('rpss_beneficial_user_name',50)->nullable();
            $table->tinyInteger('rpss_is_mortgaged');
            $table->date('rpss_is_mortgaged_date')->nullable();
            $table->tinyInteger('rpss_is_levy');
            $table->tinyInteger('rpss_is_auction');
            $table->tinyInteger('rpss_is_protest');
            $table->date('rpss_is_protest_date')->nullable();
            $table->tinyInteger('rpss_is_idle_land');
            $table->decimal('rpss_mortgage_amount',20,2)->nullable();
            $table->unsignedBigInteger('rpss_mortgage_to_code')->nullable();
            $table->string('rpss_mortgage_cancelled',50)->nullable();
            $table->date('rpss_mortgage_exec_date')->nullable();
            $table->string('rpss_mortgage_exec_by',50)->nullable();
            $table->string('rpss_mortgage_certified_before',50)->nullable();
            $table->date('rpss_mortgage_notary_public_date')->nullable();
            $table->integer('rpss_registered_by');
            $table->integer('rpss_modified_by');
            $table->timestamps();

            $table->foreign('rp_code')
              ->references('id')->on('rpt_properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpt_property_statuses');
    }
}
