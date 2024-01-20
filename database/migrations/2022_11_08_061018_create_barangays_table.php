<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('brgy_code')->comment('barangay Number');
            $table->integer('reg_no');
            $table->integer('prov_no');
            $table->integer('mun_no');
            $table->string('brgy_name');
            $table->string('brgy_area_code');
            $table->string('brgy_office');
            $table->string('dist_code', 10);
            $table->string('brgy_display_for_bplo');
            $table->string('brgy_display_for_rpt');
            $table->string('brgy_display_for_rpt_locgroup');
            $table->integer('is_active')->default('0');
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
        Schema::dropIfExists('barangays');
    }
}
