<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitFeeCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permit_fee_category', function (Blueprint $table) {
            $table->id();
            $table->integer('bussiness_classifiaction_id');
            $table->integer('bussiness_activities_id');
            $table->string('code');
            $table->string('category_Description');
            $table->double('fee_amount', 8, 2)->comment('Fee Amount');
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
        Schema::dropIfExists('permit_fee_category');
    }
}
