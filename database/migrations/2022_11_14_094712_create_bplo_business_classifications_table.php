<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_classifications', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->integer('tax_type_id');
            $table->string('taxclass_taxtype_classification_code')->comment('taxclass_code+taxtype_code+Classification_code');
            $table->string('bbc_classification_code');
            $table->text('bbc_classification_desc')->nullable();
            $table->integer('is_active')->default('0');
            $table->integer('created_by')->default('0');
            $table->datetime('created_date')->nullable();
            $table->integer('updated_by')->default('0');
            $table->datetime('updated_date')->nullable();
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
        Schema::dropIfExists('bplo_business_classifications');
    }
}
