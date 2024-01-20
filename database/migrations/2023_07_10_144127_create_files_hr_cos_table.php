<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesHrCosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_hr_cos', function (Blueprint $table) {
            $table->id();
            $table->integer('hrcos_id')->comment('ref-Table: hr_change_of_schedule.hrcos_id');
            $table->string('hrcos_file_name')->comment('filename');
            $table->string('hrcos_file_path')->comment('file path');
            $table->string('hrcos_file_type')->length(50)->comment('file type');
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
        Schema::dropIfExists('files_hr_cos');
    }
}
