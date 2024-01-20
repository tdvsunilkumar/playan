<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveColumnWelfareSwaDependent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welfare_swa_dependent', function(Blueprint $table) {
            $table->string('wsd_is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('welfare_swa_dependent', function(Blueprint $table) {
            $table->dropColumn('wsd_is_active');
        });
    }
}
