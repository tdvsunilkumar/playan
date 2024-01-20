<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPlantTreesAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_plant_trees_appraisals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code')->nullable();
            $table->unsignedBigInteger('rp_planttree_code');
            $table->integer('rvy_revision_year');
            $table->unsignedBigInteger('rvy_revision_code');
            $table->unsignedBigInteger('pc_class_code');
            $table->unsignedBigInteger('ps_subclass_code');
            $table->decimal('rpta_total_area_planted',14,5);
            $table->decimal('rpta_non_fruit_bearing',14,5);
            $table->decimal('rpta_fruit_bearing_productive',14,5);
            $table->decimal('rpta_fruit_bearing_non_productive',14,5);
            $table->date('rpta_date_planted');
            $table->decimal('rpta_unit_value',14,5);
            $table->decimal('rpta_market_value',14,5);
            $table->integer('rpta_taxable')->comment("1=taxable, 0=exempted");
            $table->integer('rpta_registered_by');
            $table->integer('rpta_modified_by');
            $table->timestamps();

            $table->foreign('rp_code')
              ->references('id')->on('rpt_properties')->onDelete('cascade');

            $table->foreign('rvy_revision_code')
              ->references('id')->on('rpt_revision_year')->onDelete('cascade');

            $table->foreign('pc_class_code')
              ->references('id')->on('rpt_property_classes')->onDelete('cascade');  

            $table->foreign('ps_subclass_code')
              ->references('id')->on('rpt_property_subclassifications')->onDelete('cascade');

            /*$table->foreign('ps_subclass_code')
              ->references('id')->on('rpt_property_subclassifications')->onDelete('cascade');*/          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpt_plant_trees_appraisals');
    }
}
