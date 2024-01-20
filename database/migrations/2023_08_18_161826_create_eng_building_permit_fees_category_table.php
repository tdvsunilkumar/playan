<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBuildingPermitFeesCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_building_permit_fees_category', function (Blueprint $table) {
            $table->id();
			$table->string('ebpfc_description', 100)->nullable();
			$table->Integer('ebpfc_status')->length(1)->default('0')->comment('0=inactive,1=active');
			$table->Integer('created_by')->length(1)->default('0');
			$table->Integer('updated_by')->length(1)->default('0');
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
        Schema::dropIfExists('eng_building_permit_fees_category');
    }
}
