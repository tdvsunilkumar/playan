<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngElectricalFeessPoleAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_electrical_feess_pole_attachments', function (Blueprint $table) {
            $table->id();
            $table->String('eefpa_description')->comment('Description');
            $table->double('eefpa_amount',8,2)->comment('Amount');
            $table->integer('is_active')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('eng_electrical_feess_pole_attachments');
    }
}
