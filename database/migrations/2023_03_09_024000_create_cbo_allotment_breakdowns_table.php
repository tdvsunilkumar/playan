<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCboAllotmentBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbo_allotment_breakdowns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('allotment_id')->unsigned()->comment('cbo_allotment_obligations');
            $table->integer('budget_breakdown_id')->unsigned()->comment('cbo_budget_breakdowns');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->double('amount')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cbo_allotment_breakdowns');
    }
}
