<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBuildingPermitFeesSet4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_building_permit_fees_set4', function (Blueprint $table) {
            $table->id('ebpfs4_id');
			$table->double('ebpfs4_range_from',8,2)->unsigned()->default(0);
			$table->double('ebpfs4_range_to',8,2)->unsigned()->default(0);
			$table->double('ebpfs4_fees',8,2)->unsigned()->default(0);
			$table->Integer('ebpfs4_status')->length(1)->default('0')->comment('0 = inactive, 1 = active');
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
        Schema::dropIfExists('eng_building_permit_fees_set4');
    }
}
