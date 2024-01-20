<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoCemeteriesListDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_cemeteries_list_details', function (Blueprint $table) {
            $table->id();
			$table->integer('ecl_id')->length(11)->comment('ref-Table : eco_cemeteries_lists.id');
			$table->integer('ecl_block')->length(11)->comment('ref-Table : eco_cemeteries_lists.id ( Block)');
			$table->integer('ecl_lot')->length(11)->comment('Number of Lot');
			$table->integer('ecl_status')->length(1)->default('0')->comment('ecl_status');
			$table->integer('status')->length(1)->comment('active = 1, inactive = 0');
			$table->integer('created_by')->length(11)->default('0')->comment('reference hr_employee.p_code of the system who registered the details');
            $table->integer('updated_by')->length(11)->default('0')->comment('reference hr_employee.p_code of the system  who update the details');
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
        Schema::dropIfExists('eco_cemeteries_list_details');
    }
}
