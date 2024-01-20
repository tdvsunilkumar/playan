<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIcdCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icd_codes', function (Blueprint $table) {
            $table->id();
            $table->string('icd10_code')->length(50)->default('0');
            $table->integer('icd10_group_id')->length(11)->default('0');
            $table->integer('icd10_case_rate')->length(11)->default('0');
            $table->integer('icd10_pro_fee')->length(11)->default('0');
            $table->integer('icd10_institution_fee')->length(11)->default('0');
            $table->integer('icd_is_active')->default(1);
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
        Schema::dropIfExists('icd_codes');
    }
}
