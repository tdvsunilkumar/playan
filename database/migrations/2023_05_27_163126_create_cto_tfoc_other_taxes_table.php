<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoTfocOtherTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_tfoc_other_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('tfoc_id')->length(11)->default('0')->comment('Ref-Table: cto_tfocs.id');
            $table->integer('otaxes_gl_id')->length(11)->default('0');
            $table->integer('otaxes_sl_id')->length(11)->default('0');
            $table->double('otaxes_percent',8,2)->length(11)->default('0');
            $table->integer('tfoc_is_applicable')->length(11)->default('0');
            $table->integer('otaxes_status')->length(11)->default('0');
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
        Schema::dropIfExists('cto_tfoc_other_taxes');
    }
}
