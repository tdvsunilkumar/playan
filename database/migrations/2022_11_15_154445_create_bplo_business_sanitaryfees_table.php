<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessSanitaryfeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_sanitaryfees', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id');
            $table->integer('tax_type_id');
            $table->integer('bbc_classification_code')->comment('foreign key of bplo_business_classifications');
            $table->integer('bba_code')->comment('foreign key of bplo_business_activities');
            $table->string('bsf_code')->nullable();
            $table->integer('bsf_fee_option')->comment('0=None, 1=Basic Fee, 2=by Category, 3=By Area');
            $table->double('bsf_fee_amount', 8, 2);
            $table->integer('bsf_tax_schedule')->comment('1=Annual, 2=Quarterly. Applicable to Fee Option: Basic By Activity');
            $table->integer('bsf_fee_schedule_option');
            $table->string('bsf_sched');
            $table->string('bsf_category_code')->nullable();
            $table->string('bsf_category_description')->nullable();
            $table->double('bsf_area_minimum', 8, 2)->nullable();
            $table->double('bsf_area_maximum', 8, 2)->nullable();
            $table->string('bsf_revenue_code');
            $table->string('bsf_remarks');
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
        Schema::dropIfExists('bplo_business_sanitaryfees');
    }
}
