<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CtoTfocBasis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_tfoc_basis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('basis_name', 100);
            $table->string('basis_ref_table', 50)->nullable();
            $table->string('basis_ref_field', 50)->nullable();
            $table->integer('basis_is_retire')->length(1)->default(0);
            $table->boolean('basis_status')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cto_tfoc_basis');
    }
}
