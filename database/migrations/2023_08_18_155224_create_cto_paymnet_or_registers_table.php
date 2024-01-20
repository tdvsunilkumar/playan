<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymnetOrRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_paymnet_or_registers', function (Blueprint $table) {
            $table->id();
            $table->integer('cpot_id')->comment('ref-Table : cto_payment_or_types.id');
            $table->integer('cpor_series')->comment('combination of cto_payment)or_type(short_name) and coa_no example : AF051,#091');
            $table->integer('ora_from')->comment('OR From');
            $table->integer('ora_to')->comment('OR To');
            $table->integer('or_count')->comment('OR Count ora_to MINUS ora_from... count how many usable OR pages');
            $table->integer('coa_no')->comment('Commission on Audit Series No.');
            $table->integer('cpor_status')->comment('Status')->default('0');
            $table->text('ora_document')->comment('Document Name');
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
        Schema::dropIfExists('cto_paymnet_or_registers');
    }
}
