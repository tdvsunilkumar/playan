<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCivIsActiveToHoInventoryPostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ho_inventory_posting', function (Blueprint $table) {
            $table->tinyInteger('civ_is_active')->after('updated_by')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ho_inventory_posting', function (Blueprint $table) {
            $table->dropColumn('civ_is_active');
        });
    }
}
