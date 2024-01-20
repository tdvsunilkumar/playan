<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaxCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create(
            'tax_categories', function (Blueprint $table){
                $table->bigIncrements('id');
                $table->string('tax_category_code');
                $table->text('tax_category_desc')->nullable();
                $table->text('tax_category_complete_description')->nullable();
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
        //Schema::dropIfExists('tax_categories');
    }
}
