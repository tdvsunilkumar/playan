<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptLandUnitValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_land_unit_values', function (Blueprint $table) {
            $table->id();
            $table->integer('loc_local_code')->comment('Foreign rpt_locality.loc_local_id');
            $table->integer('loc_group_brgy_no')->comment('foreign barangay.brgy_id');
            $table->integer('pc_class_code')->comment('foreign key rpt_property_class.pc_class_id');
            $table->integer('ps_subclass_code')->comment('foreign key rpt_property_subclassification.ps_subclass_id');
            $table->integer('pau_actual_use_code')->comment('foreign key rpt_poprerty_actual_use.pau_actual_use_id');
            $table->string('lav_location_name',50);
            $table->integer('rvy_revision_year')->comment('foreign key rpt_revision_year.rvy_revision_yearid');
            $table->double('lav_unit_value',14,3)->comment('Unit value of the land');
            $table->string('lav_unit_measure',20);
            $table->string('rls_code',10);
            $table->integer('rls_percent');
            $table->string('rls_description',50);
            $table->double('lav_strip_unit_value',14,3)->comment('strip unit value');
            $table->integer('lav_strip_is_active')->default(1);
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
        Schema::dropIfExists('rpt_land_unit_values');
    }
}
