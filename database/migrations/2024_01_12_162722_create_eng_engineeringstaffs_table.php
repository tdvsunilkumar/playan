<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngEngineeringstaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_engineeringstaffs', function (Blueprint $table) {
            $table->id();
            $table->Integer('ees_employee_id')->length(11)->comment('Ref-Table: hr_employee.id');
            $table->Integer('ees_department_id')->length(11)->comment('Ref-Table: hr_department.id');
            $table->string('ees_position')->length(255)->nullable()->comment('Position of employee');
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
        Schema::dropIfExists('eng_engineeringstaffs');
    }
}
