<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptLocalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_locality', function (Blueprint $table) {
            $table->id();
            $table->integer('mun_no');
            $table->string('loc_local_code')->unique(); 
            $table->string('loc_local_name');
            $table->string('loc_address');
            $table->string('loc_telephone_no');
            $table->string('loc_fax_no');
            $table->string('loc_mayor');
            $table->string('loc_administrator_name');
            $table->string('loc_budget_officer_name');
            $table->string('loc_budget_officer_position');
            $table->string('loc_treasurer_name');
            $table->string('loc_treasurer_position');
            $table->string('loc_chief_land_tax')->nullable();
            $table->string('loc_chief_land_tax_position')->nullable();
            $table->string('loc_assessor_name')->nullable();
            $table->string('loc_assessor_position')->nullable(); 
            $table->string('loc_assessor_assistant_name')->nullable();
            $table->string('loc_assessor_assistant_position')->nullable();
            $table->integer('loc_accountant_id')->nullable();
            $table->string('loc_accountant_position')->nullable();
            $table->integer('loc_chief_bplo_id')->nullable();
            $table->string('loc_chief_bplo_position')->nullable();
            $table->integer('loc_welfare_head_id')->nullable();
            $table->string('loc_welfare_head_position')->nullable();
            $table->integer('loc_group_default_barangay_id')->default('0')->nullable();
            $table->string('department')->comment('1=RPT, 2=BPLO, 3=CSWDO')->nullable();
            $table->integer('is_active')->default('0');
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
        Schema::dropIfExists('rpt_locality');
    }
}
