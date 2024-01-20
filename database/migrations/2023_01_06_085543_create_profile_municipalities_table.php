<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileMunicipalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('profile_municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('mun_code',5)->comment('unique code');
            $table->string('reg_no',5)->comment('foreign key profile_region.reg_no');
            $table->string('prov_no',5)->comment('foreign key profile_province.prov_no');
            $table->string('mun_no',5);
            $table->string('mun_desc',50);
            
            $table->string('mun_zip_code',10);
            $table->string('mun_area_code',10);
            $table->integer('mun_display_for_bplo')->default(0);
            $table->integer('mun_display_for_rpt')->default(0);
            $table->integer('mun_display_for_welfare')->default(0);
            $table->integer('mun_display_for_accounting')->default(0);
            $table->integer('uacs_code')->nullable();
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
        Schema::dropIfExists('profile_municipalities');
    }
}
