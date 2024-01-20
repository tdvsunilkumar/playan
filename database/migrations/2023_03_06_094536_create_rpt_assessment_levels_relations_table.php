<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptAssessmentLevelsRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        
        Schema::create('rpt_assessment_levels_relations', function (Blueprint $table) {
            $table->id();
            $table->integer('assessment_id')->comment('Foreign rpt_assessment_levels.id');
            $table->double('minimum_unit_value',14,2);
            $table->double('maximum_unit_value',14,2);
            $table->integer('assessment_level');
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('rpt_assessment_levels_relations');
    }
}
