<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyCertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    
    public function up()
    {
        Schema::create('rpt_property_certs', function (Blueprint $table) {
            $table->id();
            $table->integer('rvy_revision_year')->comment('foreign details rpt_revision_year.rvy_revision_year');
            $table->date('rpc_year')->nullable()->comment('Current Year');
            $table->string('rpc_control_no',10)->nullable();
            $table->integer('rpc_owner_code')->comment('foreign details clients.id. Refer to the property owner');
            $table->integer('rpc_requestor_code')->comment('foreign details clients.id. Refer to the property owner');
            $table->integer('rpc_city_assessor_code')->comment('foreign details hr_employees.id. Refer to the City Assessor appraiser');
            $table->integer('rpc_certified_by_code')->comment('foreign details clients.id. Refer to the property owner');
            $table->string('rpc_certified_by_position',100);
            $table->date('rpc_date');
            $table->string('rpc_or_no',10)->nullable();
            $table->date('rpc_or_date');
            $table->double('rpc_or_amount',14,3)->nullable();
            $table->integer('rpc_cert_type')->comment('1=Property Holding, 2=No Landholding, 3=No Improvement-Portion,4=No Improvement-Whole, 5=With Improvement, Tru Mechine-Copy,0=All Certification');
            $table->string('rpc_remarks',100)->nullable();
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
        Schema::dropIfExists('rpt_property_certs');
    }
}
