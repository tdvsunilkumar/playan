<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateEngBldgScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_bldg_scopes', function (Blueprint $table) {
            $table->id();
            $table->string('ebs_description',75);
            $table->integer('ebs_is_active')->default('0');
			$table->integer('ebs_is_building')->default('0');
			$table->integer('ebs_is_sanitary')->default('0');
			$table->integer('ebs_is_mechanical')->default('0');
			$table->integer('ebs_is_electrical')->default('0');
			$table->integer('ebs_is_electronics')->default('0');
			$table->integer('ebs_is_excavation_and_ground')->default('0');
			$table->integer('ebs_is_civil_structural_permit')->default('0');
			$table->integer('ebs_is_architectural_permit')->default('0');
			$table->integer('ebs_is_fencing')->default('0');
			$table->integer('ebs_is_sign')->default('0');
			$table->integer('ebs_is_demolition')->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('eng_bldg_scopes');
    }
}
