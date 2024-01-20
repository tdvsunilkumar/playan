<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngOccupancyFeesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_occupancy_fees_details', function (Blueprint $table) {
            $table->id();
            $table->integer('eoa_id')->comment('Ref-Table: eng_occupancy_app.id');
            $table->integer('tfoc_id')->comment('ref-Table: eng_service.tfoc_id');
            $table->integer('agl_account_id')->comment('ref-Table: cto_tfoc.agl_account_id');
            $table->integer('sl_id')->comment('ref-Table: cto_tfoc.sl_id');
            $table->text('fees_description')->comment('Fee Description');
            $table->double('tax_amount',10,2)->comment('Amount');
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
        Schema::dropIfExists('eng_occupancy_fees_details');
    }
}
