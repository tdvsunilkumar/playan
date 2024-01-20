<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreateBploBusinessActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->integer('tax_type_id');
            $table->integer('business_classification_id');
            $table->string('taxclass_taxtype_classification_code')->comment('taxclass_code+taxtype_code+Classification_code');
            $table->integer('code')->nullable();
            $table->string('bba_code')->nullable();
            $table->text('bba_desc')->nullable();
            $table->integer('bba_per_day')->default('0');
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
        Schema::dropIfExists('bplo_business_activities');
    }
}
