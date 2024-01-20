<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoFamPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_fam_plan', function (Blueprint $table) {
            $table->id();
			$table->integer('fam_ref_id')->length(11)->comment('Application No.');
			$table->integer('cit_id')->length(11)->comment('ref-table:citizens. cit_id');
			$table->integer('age')->length(11)->comment('ref-table:citizens. cit_id');
			$table->date('fam_date');
			$table->Integer('fam_is_active')->default(0);
			$table->integer('fam_plan_created_by')->default(0);
            $table->integer('fam_plan_modified_by')->default(0);
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
        Schema::dropIfExists('ho_fam_plan');
    }
}
