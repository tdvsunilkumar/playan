<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LegalResidentialLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_residential_location', function (Blueprint $table) {
            $table->id();
            $table->integer('residential_id')->comment('ref-Table : legal-residential_name.id');
            $table->string('phase')->length(100)->nullable();
			$table->string('street')->length(100)->nullable();
			$table->string('block')->length(100)->nullable();
            $table->integer('lot_from');
            $table->integer('lot_to');
            $table->integer('lot_slot');
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
        Schema::dropIfExists('eco_residential_location');
    }
}
