<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToBploBusinessEndorsement extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        
        Schema::table('bplo_business_endorsement', function (Blueprint $table) {
            $table->string('inspection_report_attachment',250)->comment('documents')->nullable()->after('documetary_req_json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bplo_business_endorsement', function (Blueprint $table) {
           $table->dropColumn('inspection_report_attachment');
        });
    }
}
