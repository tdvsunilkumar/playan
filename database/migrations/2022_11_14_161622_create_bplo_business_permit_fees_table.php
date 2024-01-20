<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessPermitFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_permitfees', function (Blueprint $table) {
            $table->id();
            $table->integer('bpt_recno')->default(0);
            $table->integer('tax_class_id');
            $table->integer('tax_type_id');
            $table->integer('bbc_classification_code')->comment('foreign key of bplo_business_classifications');
            $table->integer('bba_code')->comment('foreign key of bplo_business_activities');
            $table->string('bpf_code')->nullable();
            $table->integer('bpt_fee_option')->comment('0=None, 1=Basic Fee, 2=by Category, 3=By Area, 4=By Tax Paid');
            $table->integer('bpt_amount_w_machine')->nullable();
            $table->integer('bpt_amount_wo_machine')->nullable();
            $table->double('bpt_permit_fee_amount', 8, 2);
            $table->integer('bpt_tax_schedule')->comment('1=Annual, 2=Quarterly. Applicable to Fee Option: Basic Fee/By Tax Paid');
            $table->integer('bpt_item_count')->nullable();
            $table->string('bpt_sched')->nullable();
            $table->double('bpt_additional_fee', 8, 2);
            $table->integer('bpt_fee_schedule_option');
            $table->string('bpt_cagetory_code')->nullable();
            $table->string('bpt_cagetory_desc')->nullable();
            $table->double('bpt_area_minimum', 8, 2)->nullable();
            $table->double('bpt_area_maximum', 8, 2)->nullable();
            $table->double('bpt_capital_asset_minimum', 8, 2)->nullable();
            $table->double('bpt_capital_asset_maximum', 8, 2)->nullable();
            $table->double('bpt_workers_no_minimum', 8, 2)->nullable();
            $table->double('bpt_workers_no_maximum', 8, 2)->nullable();
            $table->string('bpt_revenue_code');
            $table->string('bpt_remarks');
            $table->integer('is_active')->default(1); 
            $table->datetime('bpt_registered_date');
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
        Schema::dropIfExists('bplo_business_permitfees');
    }
}
