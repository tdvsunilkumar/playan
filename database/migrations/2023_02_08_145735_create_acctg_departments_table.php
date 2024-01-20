<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('acctg_department_function_id')->unsigned();
            $table->integer('hr_employee_id')->nullable();
            $table->integer('hr_designation_id')->nullable();
            $table->string('code', 40);
            $table->string('name', 255);
            $table->string('financial_code', 20);
            $table->string('shortname', 20);
            $table->string('program', 255)->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('acctg_departments');
    }
}
