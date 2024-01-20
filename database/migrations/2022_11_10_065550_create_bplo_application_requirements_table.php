<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploApplicationRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_application_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('bplo_application_id');
            $table->integer('bplo_requirement_id');
            $table->string('bplo_code_abbreviation')->length(50)->nullable();
            $table->text('bplo_req_description')->nullable();
            $table->string('bplo_app_type')->length(30)->nullable();
            $table->integer('bar_is_complied')->length(1)->default(0);
            $table->date('bar_date_sumitted')->nullable();
            $table->text('bar_remarks')->nullable();
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
        Schema::dropIfExists('bplo_application_requirements');
    }
}
