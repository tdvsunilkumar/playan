<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_module', function (Blueprint $table) {
            $table->id();
			$table->string('cm_module_desc',100);
            $table->integer('cm_is_active')->length(1);
			$table->integer('cm_created_by')->length(14);
			$table->integer('cm_modified_by')->length(14);
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
        Schema::dropIfExists('cpdo_module');
    }
}
