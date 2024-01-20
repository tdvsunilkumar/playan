<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWswaRemarksFromWelfareSocialWelfareAssistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welfare_social_welfare_assistance', function (Blueprint $table) {
            $table->text('wswa_remarks')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('welfare_social_welfare_assistance', function (Blueprint $table) {
            //
        });
    }
}
