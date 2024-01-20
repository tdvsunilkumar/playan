<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyActualUsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_actual_uses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pc_class_code')->comment('foreign key rpt_property_class.pc_class_code');
            $table->string('pau_actual_use_code',4);
            $table->string('pau_actual_use_desc',75)->nullable();
            $table->integer('pau_with_land_stripping')->comment('1=Yes,0=No');
            $table->integer('pau_is_active')->comment('1=active,0=in-active');
            $table->integer('pau_registered_by');
            $table->integer('pau_modified_by');
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
        Schema::dropIfExists('rpt_property_actual_uses');
    }
}
