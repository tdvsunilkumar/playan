<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('barangay_id')->unsigned();
            $table->string('code', 255);
            $table->string('branch_name', 255);
            $table->string('business_name', 255);
            $table->string('telephone_no', 40)->nullable();
            $table->string('mobile_no', 40)->nullable();
            $table->string('email_address', 255)->nullable();
            $table->string('fax_no', 40)->nullable(); 
            $table->string('tin_no', 40)->nullable();             
            $table->string('house_lot_no', 255)->nullable();
            $table->string('street_name', 255)->nullable();
            $table->string('subdivision', 255)->nullable();        
            $table->string('brgy_code', 20)->nullable();   
            $table->string('region', 100)->nullable();    
            $table->string('zip', 10)->nullable();   
            $table->string('country', 100)->nullable();    
            $table->text('address')->nullable();  
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('gso_suppliers');
    }
}
