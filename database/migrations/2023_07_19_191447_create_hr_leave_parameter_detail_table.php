<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrLeaveParameterDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_leave_parameter_detail', function (Blueprint $table) {
            $table->id();
			$table->integer('hrlp_id')->length(11)->default('0')->comment('ref-Table: hr_leave_parameter.hrlp_id');
			$table->integer('hrlt_id')->length(11)->default('0')->comment('ref-Table: hr_leave_type.hrlt_id');
			$table->integer('hrlpc_days')->length(11)->default('0')->comment('# Of Days');
			$table->integer('hrat_id')->length(11)->default('0')->comment('ref-Table: hr_accrual_type.hrat_id');
			$table->integer('hrlpc_credits')->length(11)->default('0')->comment('Accrual Credits');
			$table->integer('hrlpc_is_active')->length(1)->default('0');
			$table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('hr_leave_parameter_detail');
    }
}
