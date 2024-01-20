<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->integer('tax_type_id');
            $table->string('bbc_classification_code')->comment('code of bplo_business_classifications');
            $table->string('code')->comment('code');
            $table->double('bbt_grsar_minimum', 8, 2)->comment('Gross Sales Amount Range Minimum');
            $table->double('bbt_grsar_maximum', 8, 2)->comment('Gross Sales Amount Range Maximum');
            $table->double('bbt_tax_amount', 8, 2)->comment('Tax Amount'); 
            $table->double('bbt_initial_tax_amount', 8, 2)->comment('Initial Tax Amount'); 
            $table->double('bbt_taxation_percent', 8, 2)->comment('Tax Percent'); 
            $table->integer('bbt_taxation_procedure')->comment('Taxation Procedure'); 
            $table->integer('bbt_taxation_schedule')->comment('Taxation Schedule'); 
            $table->text('alloptionjson')->nullable();
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
        Schema::dropIfExists('bplo_business_taxes');
    }
}
