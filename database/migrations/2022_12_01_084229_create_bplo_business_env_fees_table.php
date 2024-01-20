<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessEnvFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_env_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->integer('tax_type_id');
            $table->integer('bbc_classification_code')->comment('foreign key of bplo_business_classifications');
            $table->integer('bba_code')->comment('foreign key of bplo_business_activities');
            $table->string('bbef_code')->nullable();
            $table->integer('bbef_fee_option')->comment('0=None, 1=Basic By Activity, 2=Basic by Category, 3=By Area(Sq.M)');
            $table->double('bbef_fee_amount', 8, 2);
            $table->integer('bbef_tax_schedule')->comment('1=Annual, 2=Quarterly. Applicable to Fee Option: Basic By Activity');
            $table->integer('bbef_fee_schedule_option');
            $table->double('bbef_fee_amount_not_in_revenue', 8, 2);
            $table->string('bbef_sched')->nullable(); 
            $table->string('bbef_category_code')->nullable();
            $table->string('bbef_category_description')->nullable();
            $table->double('bbef_area_minimum', 8, 2)->nullable();
            $table->double('bbef_area_maximum', 8, 2)->nullable();
            $table->string('bbef_revenue_code');
            $table->string('bbef_remarks');
            $table->integer('is_active')->default(1); 
            $table->datetime('bsf_registered_date');
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
        Schema::dropIfExists('bplo_business_env_fees');
    }
}
