<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrLoanLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_loan_ledger', function (Blueprint $table) {
            $table->id();
            $table->integer('hrll_employeesid')->comment('ref-Table: hr_employees.id');
            $table->integer('hrll_department_id')->comment('ref-Table: hr_employees.acctg_department_id');
            $table->integer('hrll_division_id')->comment('ref-Table: hr_employees.acctg_department__division_id');
            $table->integer('hrla_id')->comment('ref-Table: hr_loan_application.hrla_id');
            $table->integer('hrll_deduction_status')->default('0');
            $table->integer('hrll_cycle')->comment('Cycle');
            $table->double('hrll_balance',8,2)->comment('Balance');
            $table->date('hrll_payment_date')->comment('Payment Date');
            $table->double('hrll_installment_amount',8,2)->comment('Installment Amount');
            $table->double('hrll_paid_amount',8,2)->comment('Paid Amount');
            $table->date('hrll_paid_date')->comment('Paid Date');
            $table->string('hrll_payroll_ref_no')->comment('Payroll Ref #');
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
        Schema::dropIfExists('hr_loan_ledger');
    }
}
