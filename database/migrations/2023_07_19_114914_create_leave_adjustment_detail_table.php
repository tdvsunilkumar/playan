<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveAdjustmentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_leave_adjustment_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('hrlead_id')->comment('ref-Table: hr_leave_earnings_adjustment.hrlead_id');
            $table->integer('hrlt_id')->comment('ref-Table: hr_leave_type.hrlt_id');
            $table->integer('hrlad_adjustment')->comment('adjustment');
            $table->integer('hrlad_requested_by')->comment('Requested By')->default(0);
            $table->integer('hrlad_approved_by')->comment('Approved By')->default(0);
            $table->integer('hrlad_status')->comment('status')->default(0);
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
        Schema::dropIfExists('hr_leave_adjustment_detail');
    }
}
