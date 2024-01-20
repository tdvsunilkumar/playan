<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusinessEndorsement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_endorsement', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->default('0')->comment('Ref-Table: bplo_business.id')->nullable();
            $table->string('bend_year')->default('0')->comment('cto_charge_types.ctype_id')->nullable();
            $table->integer('app_type_id')->default('0')->length(1)->comment('bplo_application_type.id')->nullable();
            $table->integer('payment_mode')->default('1')->length(1)->comment('1=Annual, 2=Bi-Annual, 3=Quarterly')->nullable();
            $table->integer('endorsing_dept_id')->default('0')->comment('Ref-Table: bplo_endorsing_dept.id');
            $table->integer('tfoc_id')->default('0')->comment('cto_tfoc.tfoc_id');
            $table->double('tfoc_amount',14,2)->nullable();
            $table->integer('bend_assessment_type')->default('0')->length(1)->comment('1=LGU Assessment, 2=Bureau Of Fire Protection Assessment')->nullable();
            $table->json('documetary_req_json')->nullable();
            $table->integer('bend_status')->default('0')->length(1)->comment('0=Not Started,1=In-Progress, 2= Completed, 3=Decline')->nullable();
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
        Schema::dropIfExists('bplo_business_endorsement');
    }
}
