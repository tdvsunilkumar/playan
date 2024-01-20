<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoSuppliersContactPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_suppliers_contact_persons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('supplier_id')->unsigned();
            $table->string('contact_person', 255);
            $table->string('telephone_no', 40)->nullable();
            $table->string('mobile_no', 40);
            $table->string('email_address', 100)->nullable();
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
        Schema::dropIfExists('gso_suppliers_contact_persons');
    }
}
