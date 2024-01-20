<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoSerologyMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_serology_method', function (Blueprint $table) {
            $table->id();
			$table->Integer('ser_id')->length(11)->default('0')->comment('Ref-Table: ho_service_id where ho_service_form = 2');
			$table->string('ser_m_method')->length(100)->default('0');
			$table->string('ser_m_remarks')->length(200)->default('0')->nullable();
			$table->Integer('created_by')->default('0')->length(11);
			$table->Integer('updated_by')->default('0')->length(11);
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
        Schema::dropIfExists('ho_serology_method');
    }
}
