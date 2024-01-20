<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialWelfare\Citizen;

use App\Models\SocialWelfare\Assistance;
use App\Models\SocialWelfare\AssistanceFile;
use App\Models\SocialWelfare\AssistanceDependent;
use App\Models\SocialWelfare\RequestLetter;
use App\Models\SocialWelfare\CaseStudy;
use App\Models\SocialWelfare\CaseStudyTreatment;
use App\Models\SocialWelfare\CaseStudyFamily;

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
use App\Models\SocialWelfare\StatusTypeModel;

use App\Models\SocialWelfare\TravelClearanceMinor;
use App\Models\SocialWelfare\TMCFiles;
use App\Models\SocialWelfare\TMCMinors;
use App\Models\SocialWelfare\TMCDestinations;

use App\Models\SocialWelfare\PWD;
use App\Models\SocialWelfare\PWDFiles;
use App\Models\SocialWelfare\PWDAssociation;

class SocialWelfareClearDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Citizen::truncate();

        Assistance::truncate();
        AssistanceFile::truncate();
        AssistanceDependent::truncate();
        RequestLetter::truncate();
        CaseStudy::truncate();
        CaseStudyTreatment::truncate();
        CaseStudyFamily::truncate();

        SoloParentID::truncate();
        SPFiles::truncate();
        SPFamilyComposition::truncate();

        SeniorCitizenID::truncate();
        SCFamilyComposition::truncate();
        SCFiles::truncate();
        SCAssociation::truncate();

        TravelClearanceMinor::truncate();
        TMCFiles::truncate();
        TMCMinors::truncate();
        TMCDestinations::truncate();

        PWD::truncate();
        PWDFiles::truncate();
        PWDAssociation::truncate();
        
        //first data comment if not needed
        $soloParent = [
            'cit_id' => 1,
            'wspa_id_number' => '20-0001',
            'wspa_is_active' => 0,
            'wspa_is_renewal' => 0,
            'wspa_occupation' => 'Test Data',//dont remove
        ];

        $seniorCitizen = [
            'cit_id' => 1,
            'wsca_new_osca_id_no' => '20-0001',
            'wsca_is_active' => 0,
            'wsca_is_renewal' => 0,
            'wsca_remarks' => 'Test Data',//dont remove
        ];

        $pwd = [
            'cit_id' => 1,
            'wpaf_pwd_id_number' => '03-4919-000-0000001',
            'wpaf_application_type' => 0,
            'wpaf_is_active' => 0,
            'wpaf_control_no' => 'Test Data',//dont remove
        ];
        // first data
        if(isset($soloParent)){
            $assist = SoloParentID::updateOrCreate(
                [
                    'id' => 1,
                ],
                $soloParent
            );
        }
        if(isset($pwd)){
            $assist = PWD::updateOrCreate(
                [
                    'id' => 1,
                ],
                $pwd
            );
        }
       if( isset($seniorCitizen)){
            $assist = SeniorCitizenID::updateOrCreate(
                [
                    'id' => 1,
                ],
                $seniorCitizen
            );
        }
    }
}
