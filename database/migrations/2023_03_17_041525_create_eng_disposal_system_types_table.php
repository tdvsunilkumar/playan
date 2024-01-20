<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngDisposalSystemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('eng_disposal_system_types', function (Blueprint $table) {
            $table->id();
            $table->string('edst_description',75);
            $table->integer('edst_is_active')->default('0');
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
        Schema::dropIfExists('eng_disposal_system_types');
    }
}