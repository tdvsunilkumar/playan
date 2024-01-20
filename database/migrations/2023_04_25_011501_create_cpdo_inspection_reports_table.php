<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoInspectionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->date('cir_date')->comment('Format: [yyyy-mm-dd].');
            $table->integer('caf_id')->comment('ref-Table : cpdo_application_form.caf_id');
            $table->string('cir_zoning_class')->comment('Residencial/Commercial/Institutional');
            $table->string('cir_use_res')->comment('A.1 Existing land Use');
            $table->integer('cit_id')->comment('ref-table: cpdo_inspection_terrain. Cit_id Required at least 1 or If others will be choosing other input text will be visable');
            $table->string('citother')->comment('other')->nullable();
            $table->string('cir_north')->comment('North');
            $table->string('cir_south')->comment('South');
            $table->string('cir_east')->comment('East');
            $table->string('cir_west')->comment('West');
            $table->string('cir_long')->comment('Long');
            $table->string('cir_lat')->comment('Lat');
            $table->text('cir_decs')->comment('B3 Description');
            $table->string('cir_water_supply')->comment('Water Supply');
            $table->string('cir_power_supply')->comment('Power Supply');
            $table->string('cir_drainage')->comment('Drainage');
            $table->string('cir_other')->comment('Other (specify)')->nullable();
            $table->string('cir_remark')->comment('remark')->nullable();
            $table->string('cir_approved_by')->comment('Approved By');
            $table->date('cir_approved_date')->comment('date');
            $table->integer('cir_isapprove')->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('cpdo_inspection_reports');
    }
}
