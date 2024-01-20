<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoCemeteriesListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_cemeteries_lists', function (Blueprint $table) {
            $table->id();
		    $table->integer('brgy_id')->length(11)->comment('ref-Table barangays.id');
			$table->integer('ec_id')->length(11)->comment('ref-Table eco_cemeteries.id');
			$table->integer('ecs_id')->length(11)->comment('ref-Table : eco_cemeteries_style.id');
			$table->string('ecl_street')->nullable()->length(100)->nullable();
			$table->string('ecl_block')->length(100);
			$table->integer('ecl_lot_no_from')->length(11)->comment('Lot. No From');
			$table->integer('ecl_lot_no_to')->length(11)->comment('Lot. No. To');
			$table->integer('ecl_slot')->length(11)->nullable()->comment('Lot Slot');
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
        Schema::dropIfExists('eco_cemeteries_lists');
    }
}
