<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessFixedTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_fixed_taxes', function (Blueprint $table) {
           
            $table->id();
            $table->integer('tax_class_id')->comment('foreign details tax_class.tax_class_id');
            $table->integer('tax_type_id')->comment('foreign details tax_type.tax_type_id');
            $table->integer('bbc_classification_code')->comment('foreign key of bplo_business_classifications');
            $table->string('bba_code',2)->comment('foreign key of bplo_business_activities');
            $table->double('bft_tax_amount', 8, 2);
            $table->integer('bft_item_count');
            $table->double('bft_additional_tax', 8, 2);
            $table->integer('bft_taxation_procedure')->comment('1=Tax Rate is indicated in TAX AMOUNT, 2=ANNUAL TAX + Excess of count is multiplied by ADDITIONAL TAX,3=Rate indicated in Tax Amount is multiplied by the number of taxable items in business');
            $table->integer('bft_taxation_schedule')->comment('1=Annual, 2=Quarterly');
            $table->datetime('bft_registered_date');
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
        Schema::dropIfExists('bplo_business_fixed_taxes');
    }
}
