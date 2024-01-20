<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetupPopReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
  
    public function up()
    {
        Schema::create('setup_pop_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('stp_type');
            $table->integer('code');
            $table->string('stp_accountable_form')->comment('1=Business Permit & License, 2=Real  Property (Land Tax),3=Burial Permit,4=Community Tax - Indivitual,5=Community Tax - Crop.,6=Miscellaneous');
            $table->string('serial_no_from');
            $table->string('serial_no_to');
            $table->string('stp_qty');
            $table->integer('stp_value');
            $table->integer('stp_print')->default(1)->nullable(); 
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('setup_pop_receipts');
    }
}
