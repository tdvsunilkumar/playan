<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoPenaltyTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    
    public function up()
    {
        Schema::create('rpt_cto_penalty_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('cpt_current_year');
            $table->integer('cpt_effective_year');
            $table->double('cpt_month_1',5,2);
            $table->double('cpt_month_2',5,2);
            $table->double('cpt_month_3',5,2);
            $table->double('cpt_month_4',5,2);
            $table->double('cpt_month_5',5,2);
            $table->double('cpt_month_6',5,2);
            $table->double('cpt_month_7',5,2);
            $table->double('cpt_month_8',5,2);
            $table->double('cpt_month_9',5,2);
            $table->double('cpt_month_10',5,2);
            $table->double('cpt_month_11',5,2);
            $table->double('cpt_month_12',5,2);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpt_cto_penalty_tables');
    }
}
