<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoServiceTypeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_service_type_details', function (Blueprint $table) {
            $table->id();
			$table->integer('est_id')->length(11)->comment('ref-Table : est_service_type.est_id');
			$table->integer('eat_additional_info')->length(1)->comment('ref-Table : est_service_type.est_id');
			$table->integer('eatd_discount')->length(1)->default('0')->nullable()->comment('with 20% Discount = 1, without 20% discount = 0');
			$table->string('eatd_process_type')->length(100)->nullable();
			$table->string('eatd_amount_type')->length(100)->nullable();
			$table->integer('eatd_status')->length(1)->comment('active = 1, inactive = 0');
			$table->integer('created_by')->length(11)->default('0')->comment('reference hr_employee.p_code of the system who registered the details');
            $table->integer('updated_by')->length(11)->default('0')->comment('reference hr_employee.p_code of the system  who update the details');
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
        Schema::dropIfExists('eco_service_type_details');
    }
}
