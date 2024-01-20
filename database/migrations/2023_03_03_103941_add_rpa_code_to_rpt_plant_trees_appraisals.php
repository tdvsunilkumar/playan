<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRpaCodeToRptPlantTreesAppraisals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpt_plant_trees_appraisals', function (Blueprint $table) {
            $table->unsignedBigInteger('rpa_code')->after('rp_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rpt_plant_trees_appraisals', function (Blueprint $table) {
            $table->dropColumn('rpa_code');
        });
    }
}
