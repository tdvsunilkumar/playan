<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCitizensColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('citizens', function(Blueprint $table) {
            $table->renameColumn('cit_nationality', 'country_id')->comment('ref-Table: country.id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citizens', function(Blueprint $table) {
            $table->renameColumn('country_id', 'cit_nationality');
        });
    }
}
