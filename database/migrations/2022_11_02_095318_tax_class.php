<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaxClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tax_classes', function (Blueprint $table){
                $table->bigIncrements('id');
                $table->string('tax_class_code');
                $table->text('tax_class_desc')->nullable();
                $table->text('tax_class_complete_description')->nullable();
                $table->integer('is_active');
                $table->integer('created_by');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
