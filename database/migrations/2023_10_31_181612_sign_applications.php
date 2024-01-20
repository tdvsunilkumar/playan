<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SignApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_applications', function (Blueprint $table) {
            $table->id();
            $table->Integer('menu_group_id')->length(11)->comment('Ref-Table: menu_modules.menu_group_id');
            $table->Integer('menu_module_id')->length(11)->comment('Ref-Table: menu_modules.id');
            $table->Integer('menu_sub_id')->length(11)->comment('Ref-Table: menu_sub_modules.id');
            $table->Integer('var_id')->length(11)->comment('Ref-Table: sign_variables.id');
            $table->string('var_name')->length(255)->nullable()->comment('Ref-Table: sign_variables.var_name');
			$table->boolean('status')->default(1);
            $table->string('remarks')->length(255)->nullable()->comment('Remarks');			
			$table->Integer('created_by')->default('0')->nullable()->length(11);
			$table->Integer('updated_by')->default('0')->nullable()->length(11);
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
        Schema::dropIfExists('sign_applications');
    }
}
