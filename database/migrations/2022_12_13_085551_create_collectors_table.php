<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('collectors', function (Blueprint $table) {
            $table->id();
            $table->string('col_code');
            $table->double('col_initial', 8, 2)->comment('Intial From Amount');
            $table->double('col_initial2', 8, 2)->comment('Intial To Amount');
            $table->string('col_name')->comment('35');
            $table->string('col_desc');
            $table->string('col_type')->comment('1=1-Cedula, 2=2-Cedula,3=3-Cedula,4=4-Cedula');
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
        Schema::dropIfExists('collectors');
    }
}
