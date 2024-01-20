<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_name', 100)->nullable();
            $table->string('app_key', 100)->nullable();
            $table->string('app_secret', 100)->nullable();
            $table->string('passphrase', 100)->nullable();
            $table->string('shortcode_mask', 100)->nullable();
            $table->integer('dcs')->default(0)->comment('sms_data_code_scheming');
            $table->text('payload_url')->nullable();
            $table->text('dlr_url')->nullable();
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
        Schema::dropIfExists('sms_settings');
    }
}
