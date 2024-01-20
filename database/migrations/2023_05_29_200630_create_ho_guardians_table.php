<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoGuardiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_guardians', function (Blueprint $table) {
            $table->id();
            $table->integer('rec_card_id')->length(11)->default(0)->commit('ref-table:cho_record_card.rec_card_id');
            $table->integer('cit_id')->length(11)->default('0')->commit('ref-table:citizens.cit_id.Get Guardian Information');
            $table->integer('guardian_status')->default(1);
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
        Schema::dropIfExists('ho_guardians');
    }
}
