<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRpPropertyCodeToRpPlantTreesAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpt_plant_trees_appraisals', function (Blueprint $table) {
            $table->unsignedBigInteger('rp_property_code')->after('rp_code');
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
            $table->dropColumn('rp_property_code');
        });
    }
}
