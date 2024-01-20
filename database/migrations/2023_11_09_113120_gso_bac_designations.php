<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GsoBacDesignations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_bac_designations', function (Blueprint $table) {
            $table->id();
            $table->Integer('employee_id')->length(11)->comment('ref-Table : hr_employees.hr_employees_id (column name [fullname])');
            $table->Integer('app_id')->length(11)->comment('1=Abstract Of Canvass, 2=Resolution');
            $table->string('position')->length(255)->nullable()->comment('Ref-Table: sign_variables.var_name');
            $table->string('remarks')->length(255)->nullable()->comment('Remarks');	
            $table->boolean('is_active')->default(1);		
			$table->Integer('created_by')->default('0')->nullable()->length(11);
			$table->Integer('updated_by')->default('0')->nullable()->length(11);
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
        Schema::dropIfExists('gso_bac_designations');
    }
}
