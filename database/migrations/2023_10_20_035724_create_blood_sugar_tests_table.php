<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloodSugarTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_blood_sugar_tests', function (Blueprint $table) {
            $table->id();
            $table->Integer('lab_req_id')->length(11)->default('0')->nullable()->comment('ref-table:ho_lab_request.id');
            $table->string('lab_control_no')->length(20)->default('0')->comment('ref-table:ho_lab_request.lab_control_no');
			$table->Integer('cit_id')->length(11)->default('0')->comment('ref-table:citizens. cit_id. Get Patient details');
			$table->Integer('bs_age_days')->length(6)->default('0');
            $table->date('bs_date');
            $table->Integer('bs_lab_year')->length(4)->default('0');
            $table->Integer('bs_lab_no')->length(11)->default('0');
            $table->string('bs_lab_num')->length(20)->default('0');
            $table->string('bs_or_num')->length(20)->nullable()->default('0');
            $table->Integer('bs_type')->length(1)->default('0')->comment('ref-table:ho_services.id = Fetch service_name');
			$table->string('bs_specimen')->length(40)->default('0');
			$table->string('bs_lot_no')->length(100)->default('0');
			$table->date('bs_expiry');
            $table->string('bs_normal_value')->length(200)->default('0');
			$table->Integer('bs_result')->length(6)->default('0')->comment('0 for Negative, 1 for Positive');
			$table->string('bs_remarks')->length(100)->nullable()->default('0');
            $table->Integer('hp_code')->length(11)->default('0')->comment('hr_employees.id = Fetch fullname');
            $table->Integer('med_tech_id')->length(11)->default('0')->comment('hr_employees.id = Fetch fullname');
			$table->string('med_tech_position')->length(200)->default('0')->comment('hr_employees.designation_id  fetch description');
			$table->Integer('health_officer_id')->length(11)->default('0')->comment('hr_employees.id = Fetch fullname');
            $table->string('health_officer_position')->length(200)->default('0')->comment('hr_employees.designation_id  fetch description');
            $table->Integer('is_active')->default('0');
            $table->Integer('is_posted')->default('0');  
			$table->Integer('created_by')->default('0')->length(11);
			$table->Integer('modified_by')->default('0')->length(11);
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
        Schema::dropIfExists('blood_sugar_tests');
    }
}
