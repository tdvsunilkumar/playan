<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateRptPropertyClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_classes', function (Blueprint $table) {
            $table->id();
            $table->string('pc_class_code',10);
            $table->integer('pc_class_no');
            $table->string('pc_class_description',50);
            $table->integer('pc_unit_value_option')->comment('1=Scheduled By District,2=Scheduled By Property Location');
            $table->integer('pc_taxability_option')->comment('1=Taxable,2=Exempt');
            $table->integer('pc_is_active')->default(1);
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
        Schema::dropIfExists('rpt_property_classes');
    }
}
