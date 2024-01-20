<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoSerologyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_serology_details', function (Blueprint $table) {
            $table->id();
			$table->Integer('ser_id')->length(11)->default('0')->comment('Ref-Table: ho_serology.id');
			$table->Integer('ho_service_id')->length(11)->default('0')->comment('Ref-Table: ho_service_id');
			$table->Integer('sm_id')->length(11)->default('0')->nullable()->comment('Ref-Table: ho_serology_method.id');
			$table->string('ser_specimen')->length(20)->default('0')->nullable();
			$table->string('ser_brand')->length(20)->default('0')->nullable();
			$table->string('ser_lot')->length(20)->default('0')->nullable();
			$table->date('ser_exp')->nullable();
			$table->Integer('ser_result')->length(6)->default('0')->nullable()->comment('0 is Non-Reactive, 1 is Reactive');
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
        Schema::dropIfExists('ho_serology_details');
    }
}
