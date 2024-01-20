<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPlantTressUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_plant_tress_unit', function (Blueprint $table) {
            $table->id();
            $table->integer('pt_ptrees_code')->comment('foreign key rpt_plant_tress.pt_ptrees_code');
            $table->integer('pc_class_code')->comment('foreign key rpt_property_class.pc_class_code');
            $table->integer('ps_subclass_code')->comment('foreign key rpt_property_subclassification.ps_subclass_code');
            $table->integer('rvy_revision_year')->comment('foreign key rpt_revision_year.rvy_revision_year');
            $table->double('ptuv_unit_value',10,3);
            $table->integer('ptuv_is_active')->default(1);
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
        Schema::dropIfExists('rpt_plant_tress_unit');
    }
}
