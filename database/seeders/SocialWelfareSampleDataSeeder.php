<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialWelfare\Citizen;

use App\Models\SocialWelfare\Assistance;
use App\Models\SocialWelfare\AssistanceFile;
use App\Models\SocialWelfare\AssistanceDependent;

use App\Models\SocialWelfare\SoloParentID;
use App\Models\SocialWelfare\SPFiles;
use App\Models\SocialWelfare\SPFamilyComposition;

use App\Models\SocialWelfare\AssistanceType;
use App\Models\SocialWelfare\AssistanceTypeRequirement;
use App\Models\SocialWelfare\AssistanceRequirements;

use App\Models\SocialWelfare\SeniorCitizenID;
use App\Models\SocialWelfare\SCFamilyComposition;
use App\Models\SocialWelfare\SCFiles;
use App\Models\SocialWelfare\SCAssociation;

use DB;

class SocialWelfareSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($c=0; $c < 20; $c++) { 
            $citizen = new Citizen();
            $citizen->insert([
                'cit_last_name' => 'Last'.$c,
                'cit_first_name' => 'First'.$c,
                'cit_middle_name' => 'Middle'.$c,
                'cit_fullname' => 'Last'.$c.' First'.$c.' Middle'.$c,
                'brgy_id' => 1,
                'cit_date_of_birth' => '1999-12-12',
                'cit_age' => 23,
                'ccs_id' => 1,
                'cea_id' => 1,
                'cit_gender' => 1,
                'country_id' => 5,
                'cit_is_active' => 1,
            ]);
            $citizenID = DB::getPdo()->lastInsertId();

            $assist = new Assistance();
            $assist->insert([
                'cit_id' => $citizenID,
                'wsat_id' => 1,
                'wswa_amount' => 1000,
                'wswa_date_applied' => '2022-12-12',
                'head_cit_id' => 2,
                'wswa_social_worker' => 1
            ]);

            $soloparent = new SoloParentID();
            $soloparent->insert([
                'cit_id' => $citizenID,
                'wspa_is_active' => 1,
            ]);
            $soloID = DB::getPdo()->lastInsertId();
            for ($f=1; $f < 5; $f++) { 
                $files = new SPFiles();
                $files->insert([
                    'wspa_id' => $soloID,
                    'req_id' => $f,
                    'fwsc_name' => 'test upload.png',
                    'fwsc_type' => 'png',
                    'fwsc_size' => 0,
                    'fwsc_path' => 'uploads/socialwelfare/test.png',
                    'fwsc_is_active' => 1,
                ]);
            }

            $senior = new SeniorCitizenID();
            $senior->insert([
                'cit_id' => $citizenID,
                'wsca_is_active' => 1,
                'wsca_new_osca_id_no' => '12345'.$citizenID
            ]);
            $senior = DB::getPdo()->lastInsertId();
            for ($f=1; $f < 5; $f++) { 
                $files = new SCFiles();
                $files->insert([
                    'wsca_id' => $soloID,
                    'req_id' => $f,
                    'fwsc_name' => 'test upload.png',
                    'fwsc_type' => 'png',
                    'fwsc_size' => 0,
                    'fwsc_path' => 'uploads/socialwelfare/test.png',
                    'fwsc_is_active' => 1,
                ]);
            }
        }

    }
}
