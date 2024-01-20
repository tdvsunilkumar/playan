<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('barangay_id')->unsigned();
            $table->integer('acctg_department_id')->unsigned();
            $table->integer('acctg_department_division_id')->unsigned();
            $table->integer('hr_designation_id')->unsigned();
            $table->string('identification_no', 40);
            $table->string('firstname', 40);
            $table->string('middlename', 40)->nullable();
            $table->string('lastname', 40);
            $table->string('fullname', 255)->nullable();
            $table->string('suffix', 10)->nullable();
            $table->string('title', 40)->nullable();
            $table->string('gender', 10);
            $table->date('birthdate')->nullable();
            $table->string('c_house_lot_no', 255)->nullable();
            $table->string('c_street_name', 255)->nullable();
            $table->string('c_subdivision', 255)->nullable();        
            $table->string('c_brgy_code', 20)->nullable();   
            $table->string('c_region', 100)->nullable();    
            $table->string('c_zip', 10)->nullable();   
            $table->string('c_country', 100)->nullable();    
            $table->text('current_address')->nullable();
            $table->boolean('is_address')->default(0);
            $table->string('email_address', 255)->nullable();
            $table->string('telephone_no', 40)->nullable();
            $table->string('mobile_no', 40)->nullable();
            $table->string('fax_no', 40)->nullable();       
            $table->string('sss_no', 40)->nullable();
            $table->string('tin_no', 40)->nullable();
            $table->string('pag_ibig_no', 40)->nullable();
            $table->string('philhealth_no', 40)->nullable();
            $table->boolean('is_dept_restricted')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_employees');
    }
}
