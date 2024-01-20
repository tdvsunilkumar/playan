<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_types', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->string('type_code');
            $table->string('tax_class_type_code')->nullable();
            $table->string('column_no')->nullable();
            $table->text('tax_type_description')->nullable();
            $table->string('tax_type_short_name')->nullable();
            $table->string('tia_account_code')->nullable();
            $table->string('top')->nullable();
            $table->integer('is_active')->default('0');
            $table->integer('tax_type_is_annual')->nullable();
            $table->integer('tax_type_with_surcharge')->nullable();
            $table->integer('tax_type_with_intererest')->nullable();
            $table->integer('tax_type_is_fire_code_base')->nullable();
            $table->integer('tax_type_with_engineering_fee')->nullable();
            $table->integer('tax_category_id');
            $table->string('tax_type_initial_amount')->nullable();
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
        Schema::dropIfExists('tax_types');
    }
}
