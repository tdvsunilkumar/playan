<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyOnlineAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_online_accesses', function (Blueprint $table) {
            $table->id();
            $table->integer('clients_id')->comment('Ref-Table: clients.id');
            $table->integer('rp_property_code')->comment('Ref-Table: rpt_properties.rp_property_code');
            $table->integer('rp_code')->comment('Ref-Table: rpt_properties.rp_code');
            $table->integer('taxpayer_id')->comment('Ref-Table: clients.id Relative property');
            $table->string('tax_declaration_no')->comment('Ref-Table: rpt_properties.rp_tax_declaration_no')->nullable();
            $table->string('property_index_no')->nullable();
            $table->double('market_value',14,2);
            $table->double('assessed_value',14,2);
            $table->double('amount_due',14,2);
            $table->string('or_no')->nullable();
            $table->date('or_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer('payment_status')->length(1)->default(0);
            $table->integer('is_active')->comment('Ref-Table: cto_tfocs.id');
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
        Schema::dropIfExists('rpt_property_online_accesses');
    }
}
