<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoTopTransactionTypeCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_top_transaction_type', function (Blueprint $table) {
            $table->id();
            $table->text('ttt_table_reference')->comment('table reference');
            $table->string('ttt_desc')->comment('desc');
            $table->integer('tfoc_is_applicable')->comment('1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('cto_top_transaction_type');
    }
}
