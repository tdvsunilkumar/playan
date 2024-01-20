<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUcNewFreshToRptUpdateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpt_update_codes', function (Blueprint $table) {
           $table->unsignedBigInteger('uc_new_fresh')->comment('Options: 1=Yes,0=No')->after('uc_is_active');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rpt_update_codes', function (Blueprint $table) {
            $table->dropColumn('uc_new_fresh');
        });
    }
}
