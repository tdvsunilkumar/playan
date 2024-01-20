<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptUpdateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_update_codes', function (Blueprint $table) {
            $table->id();
            $table->string('uc_code',10);
            $table->string('uc_description',50)->nullable();
            $table->integer('uc_usage_land')->comment('Options: 1=Applicable for Land,0=Not Applicable For Land');
            $table->integer('uc_usage_building')->comment('Options: 1=Applicable for Building,0=Not Applicable For Building');
            $table->integer('uc_usage_machine')->comment('Options: 1=Applicable for Machine,0=Not Applicable For Machine');
            $table->integer('uc_change_property_of_ownership')->comment('Options: 1=Yes,0=No');
            $table->integer('uc_cancel_existing_faas')->comment('Options: 1=Yes,0=No');
            $table->integer('uc_consolidate_existing_faas')->comment('Options: 1=Yes,0=No');
            $table->integer('uc_subdivide_existing_faas')->comment('Options: 1=Yes,0=No');
            $table->integer('uc_cancel_only_one_existing_faas')->comment('Options: 1=Yes,0=No');
            $table->integer('uc_cease_tax_declaration')->comment('Options: 1=Yes,0=No');
            $table->integer('uc_revised_tax_declaration')->comment('Options: 1=Yes,0=No');
            $table->integer('uc_is_active')->comment('1=active,0=in-active');
            $table->integer('uc_registered_by');
            $table->integer('uc_modified_by');
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
        Schema::dropIfExists('rpt_update_codes');
    }
}
