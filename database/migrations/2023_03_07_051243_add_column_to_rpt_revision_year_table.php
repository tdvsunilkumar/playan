<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRptRevisionYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpt_revision_year', function (Blueprint $table) {
            $table->unsignedBigInteger('is_default_value')->default(0)->comment('0=No,1=Yes')->after('is_active');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rpt_revision_year', function (Blueprint $table) {
             $table->dropColumn('is_default_value');
        });
    }
}
