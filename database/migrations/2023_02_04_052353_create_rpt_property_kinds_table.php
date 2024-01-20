<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyKindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_kinds', function (Blueprint $table) {
            $table->id();
            $table->string('pk_code',10);
            $table->string('pk_description',30);
            $table->integer('pk_is_active')->comment('1=active,0=in-active');
            $table->integer('pk_registered_by');
            $table->integer('pk_modified_by');
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
        Schema::dropIfExists('rpt_property_kinds');
    }
}
