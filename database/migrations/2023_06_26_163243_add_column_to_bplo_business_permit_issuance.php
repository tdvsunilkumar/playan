<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBploBusinessPermitIssuance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::table('bplo_business_permit_issuance', function (Blueprint $table) {
            $table->integer('app_type_id')->comment('1-New, 2-Renew, 3-Retire')->after('business_plate_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bplo_business_permit_issuance', function (Blueprint $table) {
            $table->dropColumn('app_type_id');
        });
    }
}
