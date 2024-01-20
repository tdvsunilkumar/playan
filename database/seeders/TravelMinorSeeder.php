<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class TravelMinorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('welfare_tcm_services_fee')->insert([
            'tfoc_id' => 1,
            'wts_id' => 1,
            'sf_amount' => 500,
        ]);
        DB::table('welfare_tcm_service')->insert([
            'wts_service_name' => 1,
            'top_transaction_type_id' => 1,
            'wts_service_fee' => 500,
        ]);
    }
}
