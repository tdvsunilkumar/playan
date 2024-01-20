<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateRptPropertySubclassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_subclassifications', function (Blueprint $table) {
            $table->id();
            $table->string('pc_class_code',10)->comment('foreign key rpt_property_class.pc_class_code');
            $table->string('ps_subclass_code',4);
            $table->string('ps_subclass_desc',75);
            $table->integer('ps_is_for_plant_trees')->comment('1=display in plant/trees selection,0=not display in plant/trees selection');
            $table->integer('ps_is_active')->default(1);
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
        Schema::dropIfExists('rpt_property_subclassifications');
    }
}
