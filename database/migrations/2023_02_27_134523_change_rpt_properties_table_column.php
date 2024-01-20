<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRptPropertiesTableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpt_properties', function (Blueprint $table) {
           $table->string('rp_suffix',5)->nullable()->change();
           $table->integer('rp_administrator_code')->nullable()->change();
           $table->unsignedBigInteger('rp_property_code')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
