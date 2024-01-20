<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoLabRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_lab_requests', function (Blueprint $table) {
            $table->id();
			$table->Integer('lab_req_year')->length(4);
			$table->Integer('lab_req_no')->length(11);
			$table->string('lab_control_no')->length(20);
			$table->Integer('cit_id')->length(11)->default('0')->nullable()->comment('ref-table:citizens.cit_id. Get Patient details');;
			$table->string('trans_id')->length(20)->default('0')->comment('ref-table:cto_top_transactions.id');
            $table->string('tfoc_id')->length(20)->default('0')->comment('ref-table:cto_tfoc.tfoc_id');
			$table->Integer('agl_account_id')->length(11)->nullable()->default('0')->comment('ref-Table: cto_tfoc.agl_account_id');
			$table->Integer('sl_id')->length(11)->comment('ref-Table: cto_tfoc.sl_id');
			$table->Integer('top_transaction_type_id')->length(11)->default('0')->comment('ref-table:cto_top_transaction_type. top_transaction_type_id');
			$table->Integer('lab_req_age')->length(11)->default('0');
			$table->Integer('lab_req_amount')->length(6)->default('0');
			$table->string('lab_req_or')->length(6)->default('20');
			$table->string('lab_req_diagnosis')->length(100)->nullable()->default('0');
			$table->Integer('lab_req_created_by')->default('0')->length(11);
			$table->Integer('lab_req_modified_by')->default('0')->length(11);
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
        Schema::dropIfExists('ho_lab_requests');
    }
}
