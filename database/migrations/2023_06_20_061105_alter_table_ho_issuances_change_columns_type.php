<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableHoIssuancesChangeColumnsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ho_issuances', function (Blueprint $table) {
            $table->integer('issuance_uom')->change();
            $table->renameColumn('receiver_name', 'receiver_id');
            $table->renameColumn('receiver_brgy', 'brgy_id');
            $table->renameColumn('issuance_item_name', 'item_id');
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
            //
        });
    }
}
