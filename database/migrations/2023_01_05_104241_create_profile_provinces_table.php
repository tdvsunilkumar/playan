<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateProfileProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('prov_code',15)->comment('province code');
            $table->string('reg_no',5)->comment('foreign key profile_region.reg_no');
            $table->string('prov_no',5)->comment('province designation');
            $table->string('prov_desc',150)->comment('province designation');
            $table->integer('is_active')->default(1); 
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
        Schema::dropIfExists('profile_provinces');
    }
}
