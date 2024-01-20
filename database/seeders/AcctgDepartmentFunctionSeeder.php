<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcctgDepartmentFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $functions = [
            [
                'code' => "GS",
                'name' => "General Services",
                'created_by' => 1
            ], 
            [
                'code' => "ES",
                'name' => "Economic Services",
                'created_by' => 1
            ], 
            [
                'code' => "SS",
                'name' => "Social Services",
                'created_by' => 1
            ]
        ];

        foreach ($functions as $function) {
            DB::table('acctg_departments_functions')->insert($function);
        }
    }
}
