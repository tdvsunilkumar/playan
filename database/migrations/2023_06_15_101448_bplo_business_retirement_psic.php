<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusinessRetirementPsic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_retirement_psic', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->default(0);
            $table->integer('busnret_id')->comment('Ref-Table: bplo_business_retirement.id')->default(0);
            $table->integer('subclass_id')->comment('Ref-Table: psic_subclass.id selected')->default(0);
            $table->integer('busnret_no_units')->comment('Ref-Table: bplo_business_psic.busp_no_units')->default(0);
            $table->double('busnret_capital_investment',14,2)->comment('Ref-Table: bplo_business_psic.busp_capital_investment')->default(0);
            $table->double('busnret_essential',14,2)->comment('Ref-Table: bplo_business_psic.busp_essential')->default(0);
            $table->double('busnret_non_essential',14,2)->comment('Ref-Table: bplo_business_psic.busp_non_essential')->default(0);
            $table->double('busnret_total_gross',14,2)->comment('(busnret_essential+busnret_non_essential)')->default(0);
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
         Schema::dropIfExists('bplo_business_retirement_psic');
    }
}
