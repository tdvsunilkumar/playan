<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEconDataCemeteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_data_cemeteries', function (Blueprint $table) {
            $table->id();
            $table->integer('brgy_id')->comment('Ref-Table: barangays.id');
            $table->string('cem_name', 100);
            $table->string('remark', 250);
            $table->integer('status');
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
        Schema::dropIfExists('eco_data_cemeteries');
    }
}
