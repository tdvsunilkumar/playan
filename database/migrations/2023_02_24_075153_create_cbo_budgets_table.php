<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCboBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbo_budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id')->unsigned()->comment('acctg_departments');
            $table->integer('division_id')->unsigned()->comment('acctg_departments_divisions');
            $table->integer('fund_code_id')->unsigned()->comment('acctg_fund_codes');
            $table->year('budget_year');
            $table->text('remarks')->nullable();
            $table->double('total_budget')->nullable();
            $table->string('status', 40)->default('draft');
            $table->boolean('is_locked')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->integer('disapproved_by')->unsigned()->nullable();
            $table->text('disapproved_remarks')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
            // $table->id();
            // $table->integer('dept_id')->comment('foreign key acctg_department.dept_code');
            // $table->integer('ddiv_id')->comment('foreign key acctg_department_division.ddiv_code');
            // $table->integer('fc_code')->comment('foreign key acctg_fund_codes.fc_code');
            // $table->integer('bud_year')->nullable();
            // $table->integer('agl_id')->comment('foreign key acctg_account_general_ledgers.agl_code');
            // $table->integer('bud_budget_quarter')->nullable();
            // $table->integer('bud_budget_annual')->nullable();
            // $table->integer('bud_budget_total')->nullable();
            // $table->integer('bud_is_locked')->nullable();
            // $table->string('bud_approved_by',14)->nullable();
            // $table->date('bud_approved_date')->nullable();
            // $table->string('bud_disapproved_by',14)->nullable();
            // $table->date('bud_disapproved_date')->nullable();
            // $table->integer('bud_status')->default(1);
            // $table->integer('created_by');
            // $table->integer('updated_by');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cbo_budgets');
    }
}
