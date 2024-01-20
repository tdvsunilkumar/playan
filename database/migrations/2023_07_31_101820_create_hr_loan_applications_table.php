<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrLoanApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_loan_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('hrla_employeesid')->comment('ref-Table: hr_employees.id');
            $table->integer('hrla_department_id')->comment('ref-Table: hr_employees.acctg_department_id');
            $table->integer('hrla_division_id')->comment('ref-Table: hr_employees.acctg_department__division_id');
            $table->string('hrla_application_no')->comment('YEAR-SERIES(5digits)');
            $table->integer('hrla_loan_status')->default('0');
            $table->string('hrla_loan_description')->comment('Loan Description');
            $table->integer('hrla_id')->comment('ref-Table: hr_loan_type.hrlt_id');
            $table->double('hrla_loan_amount',8,2)->comment('Loan Amount');
            $table->double('hrla_interest_percentage',8,2)->comment('Interest Percentage');
            $table->double('hrla_interest_amount',8,2)->comment('Interest Amount');
            $table->integer('hrlc_id')->comment('ref-Table: hr_loan_cycle.hrlc_id');
            $table->double('hrla_amount_disbursed',8,2)->comment('Amount Disbursed');
            $table->double('hrla_installment_amount',8,2)->comment('Installment Amount');
            $table->date('hrla_effectivity_date')->comment('Effectivity Date');
            $table->integer('hrla_requested_by')->comment('ref-Table: hr_employees.id');
            $table->date('hrla_requested_date')->comment('Requested Date');
            $table->integer('hrla_approved_by')->comment('ref-Table: hr_employees.id');
            $table->date('hrla_approved_date')->comment('Approved Date');
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
        Schema::dropIfExists('hr_loan_applications');
    }
}
