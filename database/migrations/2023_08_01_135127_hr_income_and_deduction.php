<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrIncomeAndDeduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_income_and_deduction', function (Blueprint $table) {
            $table->id();
            $table->integer('hridt_id')->comment('ref-Table: hr_income_deduction_type.hridt_id');
            $table->string('hriad_ref_no')->comment('YEAR-SERIES(5digits)');
            $table->string('hriad_description');
            $table->double('hriad_amount',8,2);
            $table->integer('hrlc_id')->comment('ref-Table: hr_loan_cycle.hrlc_id');
            $table->integer('emp_id')->comment('ref-Table: hr_employees.id');
            $table->integer('hrla_department_id')->comment('ref-Table: hr_employees.acctg_department_id');
            $table->integer('hrla_division_id')->comment('ref-Table: hr_employees.acctg_department__division_id');
            $table->date('hriad_effectivity_date');
            $table->double('hriad_balance',8,2)->comment('Interest Percentage');
            $table->integer('hriad_approved_by')->nullable()->comment('ref-Table: hr_employees.id');
            $table->date('hriad_approved_date')->nullable()->comment('Approved Date');
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
        Schema::dropIfExists('hr_income_and_deduction');
    }
}
