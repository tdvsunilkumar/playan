<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploSystemParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_system_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('bsp_local_code')->length(5)->comment('default is [000]');
            $table->string('bsp_local_name')->length(50)->comment('Locality Name');
            $table->string('bsp_address')->length(100)->comment('defult is [City Hall]');
            $table->string('bsp_telephone_no')->length(20);
            $table->string('bsp_fax_no')->length(20);
            $table->string('bsp_governor_mayor')->length(150);
            $table->string('bsp_administrator_name')->length(100);
            $table->string('bsp_budget_officer_name')->length(100);
            $table->string('bsp_budget_officer_position')->length(75);
            $table->string('bsp_treasurer_name')->length(100);
            $table->string('bsp_treasurer_position')->length(75);
            $table->string('bsp_accountant_name')->length(100);
            $table->string('bsp_accountant_position')->length(75);
            $table->string('bsp_chief_bplo_name')->length(100);
            $table->string('bsp_chief_bplo_position')->length(75);
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('bplo_system_parameters');
    }
}
