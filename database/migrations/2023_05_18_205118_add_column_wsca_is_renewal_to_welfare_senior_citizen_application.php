<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWscaIsRenewalToWelfareSeniorCitizenApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welfare_seniors_citizen_application', function (Blueprint $table) {
            //
            $table->string('wsca_is_renewal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('welfare_seniors_citizen_application', function (Blueprint $table) {
            $table->dropColumn('wsca_is_renewal');
        });
    }
}
