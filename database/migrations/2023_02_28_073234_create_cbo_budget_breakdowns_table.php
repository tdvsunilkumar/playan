<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCboBudgetBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbo_budget_breakdowns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('budget_id')->unsigned()->comment('cbo_budgets');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->double('quarterly_budget')->nullable();
            $table->double('annual_budget')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
            // $table->id();
            // $table->integer('bud_id')->comment('foreign key cbo_budgets.id');
            // $table->integer('dept_id')->comment('foreign key acctg_department.dept_code');
            // $table->integer('ddiv_id')->comment('foreign key acctg_department_division.ddiv_code');
            // $table->integer('fc_code')->comment('foreign key acctg_fund_codes.fc_code');
            // $table->integer('bud_year')->nullable();
            // $table->integer('agl_code')->comment('foreign key acctg_account_general_ledgers.code');
            // $table->integer('bud_budget_quarter')->nullable();
            // $table->integer('bud_budget_annual')->nullable();
            // $table->integer('bud_budget_total')->nullable();
            // $table->integer('bud_is_locked')->nullable();
            // $table->integer('bub_generated_by')->nullable();
            // $table->integer('bub_generated_date')->nullable();
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
        Schema::dropIfExists('cbo_budget_breakdowns');
    }
}
