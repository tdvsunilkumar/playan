<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIssuanceSeriesToHoIssuancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ho_issuances', function (Blueprint $table) {
            $table->string('issuance_series')->after('issuance_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ho_issuances', function (Blueprint $table) {
            $table->dropColumn('issuance_series');
        });
    }
}
