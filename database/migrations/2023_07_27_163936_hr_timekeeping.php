<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrTimekeeping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_timekeeping', function (Blueprint $table) {
            $table->id();
            $table->integer('hrtk_emp_id')->comment('ref-Table: hr_employees.id');
            $table->integer('hrtk_department_id')->comment('ref-Table: hr_employees.acctg_department_id');
            $table->integer('hrtk_division_id')->comment('ref-Table: hr_employees.acctg_department__division_id');
            $table->date('hrtk_date');
            $table->float('hrtk_total_hours', 10, 2);
            $table->float('hrtk_total_aut', 10, 2);
            $table->float('hrtk_total_overtime', 10, 2);
            $table->float('hrtk_total_leave', 10, 2);
            $table->boolean('hrtk_is_processed')->default(false);
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
        Schema::dropIfExists('hr_timekeeping');
    }
}
