<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBacRfqsSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bac_rfqs_suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rfq_id')->unsigned()->comment('bac_rfqs');
            $table->integer('supplier_id')->unsigned()->comment('gso_suppliers');
            $table->double('total_canvass')->nullable();
            $table->integer('canvass_by')->unsigned()->nullable();
            $table->date('canvass_date')->nullable();
            $table->string('contact_person', 255)->nullable();
            $table->string('contact_number', 40)->nullable();
            $table->string('email_address', 100)->nullable();
            $table->string('status', 40)->default('pending');
            $table->boolean('is_selected')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bac_rfqs_suppliers');
    }
}
