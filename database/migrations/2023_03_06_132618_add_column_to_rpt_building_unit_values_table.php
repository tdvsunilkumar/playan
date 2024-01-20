<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRptBuildingUnitValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::table('rpt_building_unit_values', function (Blueprint $table) {
            $table->unsignedBigInteger('mun_no')->comment('foreign key profile_municipalities.id')->after('id');
           $table->unsignedBigInteger('loc_group_brgy_no')->nullable()->comment('foreign barangay.brgy_id')->after('mun_no');
           $table->unsignedBigInteger('is_approve')->default('0')->after('buv_is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rpt_building_unit_values', function (Blueprint $table) {
            $table->dropColumn('mun_no');
            $table->dropColumn('loc_group_brgy_no');
            $table->dropColumn('is_approve');
        });
    }
}
