<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoRecordCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_record_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('cit_id')->length(11)->default(0);
            $table->string('rec_card_num')->length(11)->default('0');
            $table->string('rec_card_occupation')->length(60)->default('0');
            $table->integer('rec_card_status')->default(1);
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
        Schema::dropIfExists('ho_record_cards');
    }
}
