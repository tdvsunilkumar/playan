<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\SocialWelfare\AssistanceType;
use App\Models\SocialWelfare\AssistanceTypeRequirement;
use App\Models\SocialWelfare\AssistanceRequirements;
use App\Models\SocialWelfare\StatusTypeModel;

use App\Models\SocialWelfare\TypeResidency;

use App\Models\SocialWelfare\TypeDisability;
use App\Models\SocialWelfare\CauseDisability;
use App\Models\SocialWelfare\CauseDisabilityAquire;
use App\Models\SocialWelfare\TypeOccupation;
use App\Models\SocialWelfare\EmploymentStatus;
use App\Models\SocialWelfare\EmploymentCategory;
use App\Models\SocialWelfare\EmploymentType;

use App\Models\SocialWelfare\SoloParentID;
use App\Models\SocialWelfare\SeniorCitizenID;
use App\Models\SocialWelfare\PWD;

class SocialWelfareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssistanceType::truncate();
        AssistanceTypeRequirement::truncate();
        AssistanceRequirements::truncate();
        StatusTypeModel::truncate();

        TypeResidency::truncate();

        TypeDisability::truncate();
        CauseDisability::truncate();
        CauseDisabilityAquire::truncate();
        TypeOccupation::truncate();
        EmploymentStatus::truncate();
        EmploymentCategory::truncate();
        EmploymentType::truncate();

        // can be added
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

        // Assistance Type
        $assistanceType = [
            /*1*/'Hospital Bills',
            /*2*/'Medical Assistance / Procedures',
            /*3*/'Educational Assistance',
            /*4*/'Burial Assistance',
            /*5*/'Transportation Assistance',
            /*6*/'Food Assistance',
            /*7*/'Financial/Cash Assistance',
        ];

        // Assistance Requirements
        $requirments = [
            /*1*/'Hospital Bill with complete name and signature of the Billing Clerk / Promissory Note / Certificate of Balance or Confinement (should be during or atleast one month after confinement',
            /*2*/'Medical Certificate/Abstract with signature and licence number of the attending physican (issued not later than 3months)',
            /*3*/'Barangay Certificate of Indigency',
            /*4*/'Valid ID of the client with three specimen signature(should be addressed within Palayan City)',
            /*5*/'Personal letter of the client addressed to the City Mayor',
            /*6*/'Prescription or Laboratory request / protocol(for procedures) with signature and license number if the attending physician(issued not later than 3 months)',
            /*7*/'Enrollment Assessment Form',
            /*8*/'Certificate of Enrolment / Registration',
            /*9*/'School ID of the student',
            /*10*/'Grades from the previous year attended',
            /*11*/'Unpaid Funeral Contract',
            /*12*/'Death Certificate of the deceased beneficiary',
            /*13*/'Permit to transfer/health permit(for transfer of cadaver)',
            /*14*/'Police Blotter (for victims of pick pockets, illegal recruiment, etc.)',
            /*15*/'Valid(residents outside Palayan City)',
            /*16*/'Referral Letter',
            /*17*/'Any document/record that can prove that the beneficiary is in crisis situation/in need of food assistance',
            /*18*/'Relief Distribution Sheet',
            /*19*/'Police Blotter/Medical Certificate/BFP Report / Certification for fire victims',
            /*20*/'Referral letter from other agencies',
        ];

        // Assistance Type Requirement
        $assistRequire = array(
            ['Hospital Bills'=>'Hospital Bill with complete name and signature of the Billing Clerk / Promissory Note / Certificate of Balance or Confinement (should be during or atleast one month after confinement'],
            ['Hospital Bills'=>'Medical Certificate/Abstract with signature and licence number of the attending physican (issued not later than 3months)'],
            ['Hospital Bills'=>'Barangay Certificate of Indigency'],
            ['Hospital Bills'=>'Valid ID of the client with three specimen signature(should be addressed within Palayan City)'],
            ['Hospital Bills'=>'Personal letter of the client addressed to the City Mayor'],

            ['Medical Assistance / Procedures'=>'Medical Certificate/Abstract with signature and licence number of the attending physican (issued not later than 3months)'],
            ['Medical Assistance / Procedures'=>'Prescription or Laboratory request / protocol(for procedures) with signature and license number if the attending physician(issued not later than 3 months)'],
            ['Medical Assistance / Procedures'=>'Barangay Certificate of Indigency'],
            ['Medical Assistance / Procedures'=>'Valid ID of the client with three specimen signature(should be addressed within Palayan City)'],
            ['Medical Assistance / Procedures'=>'Personal letter of the client addressed to the City Mayor'],

            ['Educational Assistance'=>'Enrollment Assessment Form'],
            ['Educational Assistance'=>'Certificate of Enrolment / Registration'],
            ['Educational Assistance'=>'School ID of the student'],
            ['Educational Assistance'=>'Grades from the previous year attended'],
            ['Educational Assistance'=>'Barangay Certificate of Indigency'],
            ['Educational Assistance'=>'Valid ID of the client with three specimen signature(should be addressed within Palayan City)'],
            ['Educational Assistance'=>'Personal letter of the client addressed to the City Mayor'],

            ['Burial Assistance'=>'Unpaid Funeral Contract'],
            ['Burial Assistance'=>'Death Certificate of the deceased beneficiary'],
            ['Burial Assistance'=>'Permit to transfer/health permit(for transfer of cadaver)'],
            ['Burial Assistance'=>'Barangay Certificate of Indigency'],
            ['Burial Assistance'=>'Valid ID of the client with three specimen signature(should be addressed within Palayan City)'],
            ['Burial Assistance'=>'Personal letter of the client addressed to the City Mayor'],

            ['Transportation Assistance'=>'Police Blotter (for victims of pick pockets, illegal recruiment, etc.)'],
            ['Transportation Assistance'=>'Valid(residents outside Palayan City)'],
            ['Transportation Assistance'=>'Barangay Certificate of Indigency'],
            ['Transportation Assistance'=>'Valid ID of the client with three specimen signature(should be addressed within Palayan City)'],
            ['Transportation Assistance'=>'Personal letter of the client addressed to the City Mayor'],

            ['Food Assistance'=>'Barangay Certificate of Indigency'],
            ['Food Assistance'=>'Valid ID of the client with three specimen signature(should be addressed within Palayan City)'],
            ['Food Assistance'=>'Valid(residents outside Palayan City)'],
            ['Food Assistance'=>'Referral Letter'],
            ['Food Assistance'=>'Any document/record that can prove that the beneficiary is in crisis situation/in need of food assistance'],
            ['Food Assistance'=>'Relief Distribution Sheet'],

            ['Financial/Cash Assistance'=>'Barangay Certificate of Indigency'],
            ['Financial/Cash Assistance'=>'Valid ID of the client with three specimen signature(should be addressed within Palayan City)'],
            ['Financial/Cash Assistance'=>'Police Blotter/Medical Certificate/BFP Report / Certification for fire victims'],
            ['Financial/Cash Assistance'=>'Referral letter from other agencies'],

        );

        //Assistance Status Type
        $statusType = [
            'Resettled',
            'Cultural Communication',
            'Refugees',
            'Evacuees',
            'Returnees',
            'Not Resettled',
            'Squatters',
            'Repatriates',
        ];

        // Senior Residence
        $typeResidence = [
            'House Owner',
            'Lessee/ Tenant',
            'Sharer',
        ];

        // PWD Type Disability
        $typeDisability = [
            'Deaf or Hard of Hearing',
            'Intellectual Disability',
            'Learning Disability',
            'Mental Disability',
            'Physical Disability(Orthopedic)',
            'Psychosocial Disability',
            'Speech and Language Impairment',
            'Visual Disability',
            'Cancer(RA11215)',
            'Rare Disease(RA10747)',
        ];

        // PWD Disability Inborn
        $disabilityInborn = [
            'ADHD',
            'Cerebral Palsy',
            'Down Syndrome',
            'Others',
        ];

        // PWD Disability Acquire
        $disabilityAcquire = [
            'Chronic Illness',
            'Cerebral Palsy',
            'Injury',
            'Others',
        ];

        // PWD Occupation Type
        $typeOccupation = [
            'Managers',
            'Professionals',
            'Technicians and Associate Professionals',
            'Celerical Support Workers',
            'Skilled Agricultural, Forestry and Fishery Workers',
            'Craft and Related Trade Workers',
            'Plant And Machine Operators and Assemblers',
            'Elementary Occupations',
            'Armed Forces Occupations',
            'Others',
        ];

        // PWD Employment Status
        $statusEmployment = [
            'Employed',
            'Unemployed',
            'Self Employed',
        ];

        // PWD Employment Category
        $categoryEmployment = [
            'Government',
            'Private',
        ];

        // PWD Employment Type
        $typeEmployment = [
            'Permanent / Regular',
            'Seasonal',
            'Casual',
            'Emergency',
        ];


        // Assistance Type
        foreach ($assistanceType as $type) { 
            $assist = new AssistanceType();
            $assist->insert([
                'wsat_description' => $type,
                'wsat_is_active' => 1,
            ]);
        }

        // Assistance Requirement
        foreach($requirments as $requirment) { 
            $assist = new AssistanceRequirements();
            $assist->insert([
                'wsr_description' => $requirment,
                'wsr_is_active' => 1,
            ]);
        }

        // Assistance Type Requirement
        foreach($assistRequire as $require) { 
            $wsat = AssistanceType::where('wsat_description',array_keys($require)[0])->first()->id;
            $wsr = AssistanceRequirements::where('wsr_description',array_values($require)[0])->first()->id;
            $assistType = new AssistanceTypeRequirement();
            $assistType->insert([
                'wsr_id' => $wsr,
                'wsat_id' => $wsat,
                'wsatr_is_active' => 1,
            ]);
        }
        
        // Assistance Status Type
        foreach($statusType as $status) { 
            $assist = new StatusTypeModel();
            $assist->insert([
                'wsst_description' => $status,
                'wsst_is_active' => 1,
            ]);
        }

        // Senior Type of Recidency
        foreach($typeResidence as $type) { 
            $assist = new TypeResidency();
            $assist->insert([
                'wstor_description' => $type,
                'wstor_is_active' => 1,
            ]);
        }

        // PWD Type of Disability
        foreach($typeDisability as $type) { 
            $assist = new TypeDisability();
            $assist->insert([
                'wptod_description' => $type,
                'wptod_is_active' => 1,
            ]);
        }

        // PWD Disability Inborn
        foreach($disabilityInborn as $type) { 
            $assist = new CauseDisability();
            $assist->insert([
                'wpcodi_description' => $type,
                'wpcodi_is_active' => 1,
            ]);
        }

        // PWD Disability Acquire
        foreach($disabilityAcquire as $type) { 
            $assist = new CauseDisabilityAquire();
            $assist->insert([
                'wpcoda_description' => $type,
                'wpcoda_is_active' => 1,
            ]);
        }

        // PWD Type of Occupation
        foreach($typeOccupation as $type) { 
            $assist = new TypeOccupation();
            $assist->insert([
                'wptoo_description' => $type,
                'wptoo_is_active' => 1,
            ]);
        }

        // PWD Employment Status
        foreach($statusEmployment as $type) { 
            $assist = new EmploymentStatus();
            $assist->insert([
                'wpsoe_description' => $type,
                'wpsoe_is_active' => 1,
            ]);
        }

        // PWD Employment Category
        foreach($categoryEmployment as $type) { 
            $assist = new EmploymentCategory();
            $assist->insert([
                'wpcoe_description' => $type,
                'wpcoe_is_active' => 1,
            ]);
        }

        // PWD Employment Type
        foreach($typeEmployment as $type) { 
            $assist = new EmploymentType();
            $assist->insert([
                'wptoe_description' => $type,
                'wptoe_is_active' => 1,
            ]);
        }

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
