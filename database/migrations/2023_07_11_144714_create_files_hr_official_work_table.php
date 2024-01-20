<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesHrOfficialWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_hr_official_work', function (Blueprint $table) {
            $table->id();
            $table->integer('hrow_id')->comment('ref-Table: hr_official_work.id');
            $table->string('fhow_file_name')->comment('filename');
            $table->string('fhow_file_path')->comment('file path');
            $table->string('fhow_file_type')->length(50)->comment('file type');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('files_hr_official_work');
    }
}
