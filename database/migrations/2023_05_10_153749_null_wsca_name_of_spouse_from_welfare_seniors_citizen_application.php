<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullWscaNameOfSpouseFromWelfareSeniorsCitizenApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welfare_seniors_citizen_application', function (Blueprint $table) {
            $table->integer('wsca_name_of_spouse')->nullable()->comment('ref-Table: citizens.cit_id')->change();
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
            //
        });
    }
}
