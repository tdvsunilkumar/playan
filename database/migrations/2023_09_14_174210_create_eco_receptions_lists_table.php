<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoReceptionsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_receptions_lists', function (Blueprint $table) {
            $table->id();
			$table->integer('barangay_id')->length(11)->comment('ref-Table barangays.id');
			$table->string('est_service_type')->length(100)->comment('Cemetery modules = 1, Event Module = 2');
			$table->integer('est_addtional_info')->length(1)->default('0')->nullable()->comment('Checked = 1, unchecked = 0');
			$table->integer('est_year_month')->length(1)->nullable()->comment('checked Yearly = 1, Checked Monthly = 2;');
			$table->integer('est_status')->length(1)->comment('active = 1, inactive = 0');
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
        Schema::dropIfExists('eco_receptions_lists');
    }
}
