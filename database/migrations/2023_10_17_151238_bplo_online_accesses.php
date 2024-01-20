<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploOnlineAccesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('bplo_online_accesss', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->comment('Ref-Table: clients.id');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id');
            $table->integer('taxpayer_id')->comment('Ref-Table: clients.id Relative Bplo');
            $table->boolean('is_synced')->default(0);
            $table->integer('is_active')->length(1)->default(0);
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
        Schema::dropIfExists('bplo_online_accesss');
    }
}
