<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBuildingPermitFeesSet2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_building_permit_fees_set2', function (Blueprint $table) {
            $table->id('ebpfs2_id');
			$table->double('ebpfs2_range_from',8,2)->unsigned()->default(0);
			$table->double('ebpfs2_range_to',8,2)->unsigned()->default(0);
			$table->double('ebpfs2_fees',8,2)->unsigned()->default(0);
			$table->Integer('ebpfs2_status')->length(1)->default('0')->comment('0 = inactive, 1 = active');
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
        Schema::dropIfExists('eng_building_permit_fees_set2');
    }
}
