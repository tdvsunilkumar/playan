<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoProjectProcurementManagementPlansStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_project_procurement_management_plans_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ppmp_id')->unsigned()->comment('gso_project_procurement_management_plans');
            $table->integer('division_id')->unsigned()->comment('acctg_departments_divisions');
            $table->string('status', 40)->default('draft');
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
        Schema::dropIfExists('gso_project_procurement_management_plans_status');
    }
}
