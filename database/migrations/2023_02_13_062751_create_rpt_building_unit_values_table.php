<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptBuildingUnitValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_building_unit_values', function (Blueprint $table) {
            $table->id();
            $table->integer('bk_building_kind_code')->comment('foreign key rpt_building_kind.bk_building_kind_code');
            $table->integer('bt_building_type_code')->comment('foreign key rpt_building_type.');
            $table->integer('rvy_revision_year')->comment('foreign key rpt_revision_year.rvy_revision_year');
            $table->double('buv_minimum_unit_value',14,3)->comment('building unit minimum unit value');
            $table->double('buv_maximum_unit_value',14,3)->comment('building unit maximum unit value');
            $table->string('buv_revision_year',10)->nullable();
            $table->integer('buv_is_active')->default(1);
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
        Schema::dropIfExists('rpt_building_unit_values');
    }
}
