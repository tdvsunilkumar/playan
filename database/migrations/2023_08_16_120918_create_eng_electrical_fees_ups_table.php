<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngElectricalFeesUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_electrical_fees_ups', function (Blueprint $table) {
            $table->id();
			$table->double('eefu_kva_range_from',8,3)->unsigned();
			$table->double('eefu_kva_range_to',8,3)->unsigned();
			$table->double('eefu_fees',8,3)->unsigned();
			$table->double('eefu_in_excess_fees',8,3)->unsigned();
			$table->Integer('eefu_status')->length(1)->default('0')->comment('0 = inactive, 1 = active');
			$table->Integer('created_by')->length(14)->default('0');
			$table->Integer('updated_by')->length(14)->default('0');
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
        Schema::dropIfExists('eng_electrical_fees_ups');
    }
}
