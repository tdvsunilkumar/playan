<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LegalHousingApplicationLoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_housing_application_loc', function (Blueprint $table) {
            $table->id();
            $table->integer('housing_application_id')->comment('ref-Table : eco_housing_application id');
			$table->integer('residential_name_id')->comment('ref-Table : eco_residential_name . Id');
            $table->integer('residential_location_id')->comment('ref-Table :  eco_residential_location . Id (dropdown phase of any)');
            $table->integer('blk_lot_id')->comment('ref-Table : eco_residentiallocation_details . Id ( Block and Lot dropdown)');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('eco_housing_application_loc');
    }
}
