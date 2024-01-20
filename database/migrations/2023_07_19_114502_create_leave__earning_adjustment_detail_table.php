<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveEarningAdjustmentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_leave_earning_adjustment_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('hrlea_id')->comment('ref-Table: hr_leave_earning_adjustment.hrlea_id');
            $table->integer('hrlt_id')->comment('ref-Table: hr_leave_type.hrlt_id');
            $table->integer('hrlpc_days')->comment('get number from Leave Parameter # Of Days');
            $table->integer('hrlead_used')->comment('Used');
            $table->integer('hrlead_balance')->comment('Balance');
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
        Schema::dropIfExists('hr_leave_earning_adjustment_detail');
    }
}
