<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrBiometricsRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_biometrics_record', function (Blueprint $table) {
            $table->id();
            $table->integer('hrbr_emp_id')->comment('ref-Table: hr_employees.id');
            $table->integer('hrtc_emp_id_no')->comment('ref-Table: hr_employees.identification_no');
            $table->integer('hrbr_department_id')->comment('ref-Table: hr_employees.acctg_department_id');
            $table->integer('hrbr_division_id')->comment('ref-Table: hr_employees.acctg_department__division_id');
            $table->date('hrbr_date');
            $table->time('hrbr_time');
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
        Schema::dropIfExists('hr_biometrics_record');
    }
}
