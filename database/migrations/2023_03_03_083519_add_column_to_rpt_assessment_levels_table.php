<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddColumnToRptAssessmentLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpt_assessment_levels', function (Blueprint $table) {
           $table->unsignedBigInteger('mun_no')->comment('foreign key profile_municipalities.id')->after('id');
           $table->unsignedBigInteger('loc_group_brgy_no')->nullable()->comment('foreign barangay.brgy_id')->after('mun_no');
           $table->unsignedBigInteger('ps_subclass_code')->comment('foreign key rpt_property_subclassifications.id')->after('loc_group_brgy_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rpt_assessment_levels', function (Blueprint $table) {
            $table->dropColumn('mun_no');
            $table->dropColumn('loc_group_brgy_no');
            $table->dropColumn('ps_subclass_code');
        });
    }
}
