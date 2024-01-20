<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBuildingPermitFeesDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_building_permit_fees_division', function (Blueprint $table) {
            $table->id();
			$table->string('ebpfd_group', 50)->nullable();
			$table->string('ebpfd_description', 200)->nullable();
			$table->Integer('ebpfc_id')->length(11)->default('0')->comment('ref-Table: eng_building_permit_fees_category.ebpfc_id');
			$table->Integer('ebpfd_feessetid')->length(11)->default('0');
			$table->Integer('ebpfd_status')->length(1)->default('0')->comment('0=inactive,1=active');
			$table->Integer('created_by')->length(11)->default('0');
			$table->Integer('updated_by')->length(11)->default('0');
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
        Schema::dropIfExists('eng_building_permit_fees_division');
    }
}
