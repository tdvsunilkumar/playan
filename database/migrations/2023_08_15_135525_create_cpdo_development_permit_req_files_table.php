<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoDevelopmentPermitReqFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_development_permit_req_files', function (Blueprint $table) {
            $table->id();
            $table->Integer('cdpr_id')->length(11)->default('0')->comment('ref-Table : cpdo_development_permit_requirements.cdpr_id');
            $table->String('cdprl_name')->comment('File Name');
            $table->String('cdprl_type')->comment('file type')->nullable();
            $table->String('cdprl_size')->comment('file size')->nullable();
            $table->String('cdprl_path')->comment('file path')->nullable();
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
        Schema::dropIfExists('cpdo_development_permit_req_files');
    }
}
