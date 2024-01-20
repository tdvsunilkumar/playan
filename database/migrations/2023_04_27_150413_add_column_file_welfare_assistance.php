<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFileWelfareAssistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files_welfare_assistance', function(Blueprint $table) {
            $table->integer('wsr_id')->comment('ref-Table: welfare_swa_requirements.id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files_welfare_assistance', function(Blueprint $table) {
            $table->dropColumn('wsr_id');
        });
    }
}
