<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploRequirementRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_requirement_relations', function (Blueprint $table) {
            $table->id();
            $table->integer('bplo_requirement_id')->comment('foreign key bplo_requirements');
            $table->integer('requirement_id')->comment('foreign key bplo_requirements');
            $table->integer('subclass_id')->comment('foreign key psic_subclasses');
            $table->integer('is_active')->default(1);
            $table->string('remark');
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
        Schema::dropIfExists('bplo_requirement_relations');
    }
}
