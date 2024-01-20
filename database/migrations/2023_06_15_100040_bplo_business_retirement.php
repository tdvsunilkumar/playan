<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusinessRetirement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_retirement', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->default(0);
            $table->integer('retire_year')->comment('Current Year');
            $table->integer('retire_month')->comment('month');
            $table->integer('retire_application_type')->comment('1=Per Line of Business,2=Entire Business');
            $table->integer('prev_app_code')->default('0')->comment('1-New, 2-Renew, 3-Retire');
            $table->string('retire_reason_ids',100)->comment('Ref-Table:bplo_retire_reason.id')->default(0);
            $table->text('retire_reason_remarks')->comment('Others: Remarks')->nullable();
            $table->date('retire_date_start')->nullable();
            $table->date('retire_date_closed')->nullable();
            $table->double('retire_bldg_area')->comment('Business Area(in sq.m)')->default('0');
            $table->integer('retire_bldg_total_floor_area')->comment('month')->default('0')->nullable();
            $table->integer('retire_employee_no_female')->default('0')->nullable();
            $table->integer('retire_employee_no_male')->default('0')->nullable();
            $table->integer('retire_employee_total_no')->default('0')->nullable();
            $table->integer('retire_employee_no_lgu')->default('0')->nullable();
            $table->integer('retire_vehicle_no_van_truck')->default('0')->nullable();
            $table->integer('retire_vehicle_no_motorcycle')->default('0')->nullable();
            $table->text('retire_remarks')->nullable();
            $table->integer('retire_status')->comment('0- Save as Draft, 1-Submit')->length(1)->default(0);
            $table->text('retire_documentary_json')->comment('Json')->nullable();
            $table->tinyInteger('retire_is_final_assessment')->default(0)->length(1)->nullable();
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
        Schema::dropIfExists('bplo_business_retirement');
    }
}
