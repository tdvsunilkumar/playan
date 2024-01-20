<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_penalties', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('Code');
            $table->string('name')->comment('Name');
            $table->text('description')->comment('Description');
            $table->double('percentage',12,2)->comment('Percentage');
            $table->integer('is_active')->default('1')->comment('1=Active,0=Inactive');
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
        Schema::dropIfExists('cpdo_penalties');
    }
}
