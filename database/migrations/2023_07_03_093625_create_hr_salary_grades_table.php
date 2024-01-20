<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrSalaryGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_salary_grades', function (Blueprint $table) {
            $table->id();
            $table->integer('hrsg_salary_grade')->unsigned()->comment('salary Grade');
            $table->integer('hrsg_step_1')->unsigned()->comment('Step 1');
            $table->integer('hrsg_step_2')->unsigned()->comment('Step 2');
            $table->integer('hrsg_step_3')->unsigned()->comment('Step 3');
            $table->integer('hrsg_step_4')->unsigned()->comment('Step 4');
            $table->integer('hrsg_step_5')->unsigned()->comment('Step 5');
            $table->integer('hrsg_step_6')->unsigned()->comment('Step 6');
            $table->integer('hrsg_step_7')->unsigned()->comment('Step 7');
            $table->integer('hrsg_step_8')->unsigned()->comment('Step 8');
            $table->integer('is_active')->default(0);
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('hr_salary_grades');
    }
}
