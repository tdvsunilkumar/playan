<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoRequestPermitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_request_permit', function (Blueprint $table) {
            $table->id();
			$table->Integer('requestor_id')->length(11)->default('0')->comment('ref-table:citizens.id get fullname');
			$table->string('brgy_id')->length(11)->default('0')->comment('ref-table:barangays.id get brgy_name, mun_desc');
			$table->date('request_date');
			$table->string('control_no')->length(20)->comment('format [year-0001] 2023-0001 incremental and resets every year');
			$table->double('request_amount',8,3)->unsigned()->default(0);
			$table->Integer('top_transaction_no')->length(20)->default('0')->nullable()->comment('ref-table:cto_top_transactions. id...... use top_transaction_no');
			$table->Integer('trans_id')->length(20)->default('0')->nullable()->comment('ref-table:cto_top_transactions. id');
			$table->Integer('cashierd_id')->length(11)->nullable()->comment('Ref-Table: cto_cashier_details.id');
			$table->Integer('cashier_id')->length(11)->nullable()->comment('Ref-Table:cto_cashier.id');
			$table->string('or_no')->length(100)->nullable()->comment('Ref-Table: cto_cashier.or_no');
			$table->date('or_date')->nullable()->comment('Ref-Table: cto_cashier.cashier_or_date');
			$table->double('or_amount',8,3)->unsigned()->nullable()->comment('Ref-Table: cto_cashier_details.tfc_amount');
			$table->Integer('is_free')->length(1)->default('0')->comment('0 = not free, 1 = free');
			$table->Integer('is_posted')->length(1)->default('0')->comment('0 = saved, 1 = posted');
			$table->Integer('status')->length(1)->default('0')->comment('0 = inactive, 1 = active');
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
        Schema::dropIfExists('ho_request_permit');
    }
}
