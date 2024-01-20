<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LegalResidentialLocationDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_residential_location_details', function (Blueprint $table) {
            $table->id();
            $table->integer('residential_id')->comment('ref-Table : legal-residential_name.id');
            $table->integer('residential_location_id')->comment('ref-Table : eco_residential_location.Id');
            $table->integer('lot_number');
			$table->integer('lot_status')->default(1);
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
        Schema::dropIfExists('eco_residential_location_details');
    }
}
