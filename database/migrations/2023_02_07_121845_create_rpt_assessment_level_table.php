<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptAssessmentLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_assessment_level', function (Blueprint $table) {
            $table->id();
            $table->string('pk_code')->comment('foreign key rpt_property_kind.pk_code')->nullable();
            $table->string('pc_class_code')->comment('foreign key rpt_property_class.pc_class_code')->nullable();
            $table->string('pau_actual_use_code')->comment('foreign key rpt_property_actual_use.pau_actual_use_code	')->nullable();
            $table->string('rvy_revision_year')->comment('foreign key rpt_revision_year.rvy_revision_year')->nullable();
            $table->decimal('al_minimum_unit_value',11,3);
            $table->decimal('al_maximum_unit_value',11,3);
            $table->decimal('al_assessment_level',11,3);
            $table->integer('is_active')->default('0');
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
        Schema::dropIfExists('rpt_assessment_level');
    }
}
