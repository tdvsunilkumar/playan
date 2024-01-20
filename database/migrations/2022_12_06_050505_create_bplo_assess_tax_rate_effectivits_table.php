<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploAssessTaxRateEffectivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_assess_tax_rate_effectivits', function (Blueprint $table) {
            $table->id();
            $table->string('tre_code')->length(14)->nullable();
            $table->integer('tre_effectivity_year')->length(4);
            $table->integer('tre_quarter')->length(4);
            $table->string('tre_ordinance_number')->length(20);
            $table->string('tre_remarks')->length(100)->nullable();
            $table->datetime('tre_date');
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
        Schema::dropIfExists('bplo_assess_tax_rate_effectivits');
    }
}
