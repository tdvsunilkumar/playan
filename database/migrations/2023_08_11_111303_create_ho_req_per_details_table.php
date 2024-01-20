<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoReqPerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_req_per_details', function (Blueprint $table) {
            $table->id();
			$table->Integer('req_permit_id')->length(11)->comment('ref-table:ho_request_permit.id');
			$table->Integer('requestor_id')->length(11)->default('0')->comment('ref-table:citizens.id');
			$table->Integer('service_id')->length(11)->default('0')->comment('ref-table:ho_service.id');
			$table->Integer('tfoc_id')->length(11)->default('0')->comment('ref-table:cto_tfoc.id');
			$table->Integer('agl_account_id')->length(11)->default('0')->comment('ref-Table:cto_tfoc.agl_account_id');
			$table->Integer('sl_id')->length(11)->default('0')->comment('ref-Table:cto_tfoc.sl_id');
			$table->double('permit_fee',8,3)->unsigned()->default(0)->comment('ref-form:Fee');
			$table->Integer('is_free')->length(1)->default('0')->comment('0=not free,1=free');
			$table->Integer('status')->length(1)->default('0')->comment('0=inactive,1=active');
			$table->Integer('created_by')->length(1)->default('0');
			$table->Integer('updated_by')->length(1)->default('0');
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
        Schema::dropIfExists('ho_req_per_details');
    }
}
