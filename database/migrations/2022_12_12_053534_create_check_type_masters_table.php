<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckTypeMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_type_masters', function (Blueprint $table) {
            $table->id();
            $table->string('ctm_code')->comment('Code');
            $table->string('ctm_description')->comment('Description');
            $table->string('ctm_short_name')->nullable();
            $table->integer('is_active')->default(1); 
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
        Schema::dropIfExists('check_type_masters');
    }
}
