<?php

return [
	'lav_unit_measure' => [
		'1' => 'Square Meter',
		'2' => 'Hectare'
	],
	'lav_unit_measure_short' => [
		'1' => 'Sq. m.',
		'2' => 'Has.'
	],
	'rptKinds' => [
		'B' => '1',
		'L' => '2',
		'M' => '3'
	],
	'update_codes_land' => [
		'DC' => '6',
		'TR' => '25',
		'SD' => '18',
		'PC' => '14',
		'RC' => '16',
		'CS' => '7',
		'DP' => '10',
		'RE' => '17',
		'DUP'=> '11',
		'RF' => '15',
		'DE' => '8',
		'DT' => '9',
		'GR' => '26',
		'SSD'=> '24'
	],
	'arrBusinessApplicationStatus' => [
		'0' => 'Not Completed',
		'1' => 'For Verification',
		'2' => 'For Endorsement',
		'3' => 'For Assessment',
		'4' => 'For Payment',
		'5' => 'For Issuance',
		'6' => 'License Issued',
		'7' => 'Declined',
		'8'=> 'Cancelled Permit'
	],
	'arrAssessmentType' => [
		'1' => 'Local Government',
		'2' => 'National Government'
	],
	'arrBusinessApplicationType' => [
		'1' => 'New',
		'2' => 'Renew',
		'3' => 'Retire'
	],
	'arrBusEndAssessmentType' => [
		'1' => 'LGU Assessment',
		'2' => 'Bureau Of Fire Protection Assessment',
	],
	'arrBusEndorsementStatus' => [  
		'0' => 'Not Started',
		'1' => 'In-Progress',
		'2' => 'Completed',
		'3' => 'Decline'
	],
	'arrCpdoStatus' => [
		'1' => 'Transaction',
		'2' => 'Inspection',
		'3' => 'Certification'
	],
	'arrCpdoStatusDetails' => [
		'1' => 'Draft',
		'2' => 'Paid',
		'3' => 'Pending',
		'4' => 'Inspected',
		'5' => 'For Submission',
		'6' => 'Recommend Approval',
		'7' => 'Noted',
		'8' => 'Approval',
		'9' => 'Done',
	],
	'arrCpdoAppModule' => [
		'1' => 'Resort',
		'2' => 'Commercial',
		'3' => 'Open Space',
		'4' => 'Institutional',
		'5' => 'Residential',
		'6' => 'Residential Subdivision',
		'7' => 'Condominium',
		'8' => 'Apartmetns/Townhouses',
		'9' => 'Dormitories',
	],
	'arrCpdoNatureApp' => [
		'1' => 'New Development',
		'2' => 'Improvement',
		'3' => 'Others',
	],
	
	'arrCpdoProjectTenure' => [
		'1' => 'Permanent',
		'2' => 'Temporary',
	],
	'arrCpdoOverland' => [
		'0' => 'None',
		'1' => 'Resort',
		'2' => 'Commercial',
		'3' => 'Open Space',
		'4' => 'Institutional',
	],
	'arrCpdoInspectionTerrain' => [
		'1' => 'Flat',
		'2' => 'Rolling', 
		'3' => 'Others',
	],
	//citizens' civil status
	'citCivilStatus' => [
		''  => 'Please Select',
		'1' => 'Single',
		'2' => 'Separated',
		'3' => 'Cohabitation (Live In)',
		'4' => 'Married',
		'5' => 'Widow/er',
		'6' => 'Divorce',
		'7' => 'Annulled',
	],
	//citizens' civil status
	'citGender' => [
		''  => 'Please Select',
		'0' => 'Male',
		'1' => 'Female',
	],
	//citizens' educational attainment
	'citEducationalAttainment' => [
		''  => 'Please Select',
		'1' => 'None',
		'2' => 'Day Care',
		'3' => 'Kindergarten',
		'4' => 'Elementary',
		'5' => 'Junior High School',
		'6' => 'Senior High School',
		'7' => 'High School',
		'8' => 'College',
		'9' => 'Vocational',
		'10' => 'Post Graduate',
	],
	//Solo Parent' File Requirements
	'spFileRequirements' => [
		'1' => 'Barangay Certificate of Being Solo Parent',
		'2' => 'Birth certificate of minor children',
		'3' => 'Death certificate of spouse, if widow',
		'4' => '1X1 Picture',
	],
	//Senior Citizens' File Requirements
	'scFileRequirements' => [
		'1' => 'Barangay Clearance',
		'2' => 'Comelect ID and/or Certificate of registration as voter of Palayan City',
		'3' => 'Birth Certificate / Passport / Marriage Contract / SSS / GSIS / any ID with date of birth',
		'4' => '1X1 Picture',
	],
	//PWD's File Requirements
	'pwdFileRequirements' => [
		'1' => 'Barangay Clearance',
		'2' => '1X1 Picture',
		'3' => 'Valid ID / Birth Certificate',
		'4' => 'Certificate of Disability Indicating Name and License Number of Physician',
	],
	//Travel Minor Clearance's File Requirements
	'tcmFileRequirements' => [
		'1' => 'Birth Certifcate of Security Paper(SECPA) of the minor',
		'2' => "Certified copy of Marriage Certificate of minor's parents, if appropriate",
		'3' => 'Notarized affidavit of consent from Parents/Guardians authorizing particular person to accompany the child in his/hertravel abroad',
		'4' => 'Certified copy of any evidence to show financial capabiliy of sponsor such as Certificate of Employment / Latest Income Tax Return / Bank / Statement etc.',
		'5' => 'Passport size picture of the minor',
		'6' => 'Passport of travelling companion',
	],
	// Municipality code based on https://psgc.gitlab.io/api/ (supposed to be in set in db or i dunno lol) - LVA
	'defaultCityCode' => [
		'region' => '03',
		'province' => '49',
		'municipality' => '19',
		'city' => 'Palayan',
		'province_name' => 'Nueva Ecija',
	],
	// checkbox tcppdf print
	'checkbox' => [
		'checked' => '<img src="./assets/images/checkbox-checked.jpeg" width="7" height="7" style="margin:5pt;">',
		'unchecked' => '<img src="./assets/images/checkbox-unchecked.jpg" width="7" height="7" style="margin:5pt;">',
	],
	//Payment Mode 
	'payMode' => [
		'1' => 'Annual',
		'2' => 'Bi-Annual',
		'3' => 'Quarterly',
	],
	//Payment Mode 
	'payModePartition' => [
		'1' => ['1'=>'Annual'],
		'2' => ['1'=>'1 st Semester','2'=>'2 nd Semester'],
		'3' => ['1'=>'1 st Quarter','2'=>'2 nd Quarter','3'=>'3 rd Quarter','4'=>'4 th Quarter']
	],
	//Payment Mode 
	'payModePartitionShortCut' => [
		'1' => ['1'=>'Annual'],
		'2' => ['1'=>'1st Sem','2'=>'2nd Sem'],
		'3' => ['1'=>'1st Qtr.','2'=>'2nd Qtr.','3'=>'3rd Qtr.','4'=>'4th Qtr.']
	],
	//Re Assessnebt Payment Mode
	'reAssessPayMode' => [
		'1' => '[Re-Assess] procedure will re-calculate current and previous years. Previous [Mode of Payment] will CHANGE based on the current selection.',
		'2' => '[Re-Assess] procedure will re-calculate current and previous years. Previous [Mode of Payment] will NOT CHANGE based on the current selection.'
	],
	// table form ho_services
	'rehoservicedepartment' => [
		'1' => 'Laboratory',
		'2' => 'Outpatient',
		'3' => 'Sanitary',
		'4' => 'Business Permit',
		'5' => 'Civil Registrar'
	],
	//table form ho_services
	'rehoserviceform' => [
		'1' => 'Hematology Form',
		'2' => 'Serology Form',
		'3' => 'Urinalysis Form',
		'4' => 'Fecalysis Form',
		'5' => 'Pregnancy Test Form',
		'6' => 'Blood Sugar Test Form',
		'7' => 'Gram Staining Test Form'
	],
	// date ranges
	'reportingDateRanges' => [
		[
			'name' =>'JAN',
			'data' => 1
		],
		[
			'name' =>'FEB',
			'data' => 2
		],
		[
			'name' =>'MAR',
			'data' => 3
		],
		[
			'name' =>'Q1',
			'data' => [1,3]
		],
		[
			'name' =>'APR',
			'data' => 4
		],
		[
			'name' =>'MAY',
			'data' => 5
		],
		[
			'name' =>'JUN',
			'data' => 6
		],
		[
			'name' =>'Q2',
			'data' => [4,6]
		],
		[
			'name' =>'JUL',
			'data' => 7
		],
		[
			'name' =>'AUG',
			'data' => 8
		],
		[
			'name' =>'SEP',
			'data' => 9
		],
		[
			'name' =>'Q3',
			'data' => [7,9]
		],
		[
			'name' =>'OCT',
			'data' => 10
		],
		[
			'name' =>'NOV',
			'data' => 11
		],
		[
			'name' =>'DEC',
			'data' => 12
		],
		[
			'name' =>'Q4',
			'data' => [10,12]
		],
		[
			'name' =>'Annual',
			'data' => [1,12]
		],
	],
	'parametersLabRequest' =>[
		'CBC' => ['hema_wbc','hema_lymph_num','hema_mid_num','hema_gran_num','hema_lymph_pct','hema_mid_pct','hema_gran_pct','hema_gran_pct','hema_hgb','hema_rbc','hema_hct','hema_mcv','hema_mch','hema_mchc','hema_rdw_cv','hema_rdw_sd'],
		'Platelet Count' => ['hema_plt','hema_mpv','hema_pdw','hema_pct'],
		'ABO Typing' => ['hema_blood_type'],
		'Rh Typing' => ['hema_blood_type'],
		'Blood Typing' => ['hema_blood_type'],
	],
	'SyphilisScreeningTest' => [
		'1' => 'Immunochromatography',
		'2' => 'RPR Qualitative Method',
		'3' => 'RPR Quantitative Method',
	],

	'taxRevenueYears' => [
		'1' => 'Advances',
		'2' => 'Current Year',
		'3' => 'Previous Years',
		'4' => 'Prior Years'
	],
	'arrPayeeType' => [
		'0' => '',
		'1' => 'Taxpayer',
		'2' => 'Citizen'
	],
	'arrHolidaysType' => [
		'1' => 'Regular'
	],
	'arrHrLogType' => [
		'1' => 'IN',
		'2' => 'OUT'
	],
	'arrHrWorkType' => [
		'1' => 'IN',
		'2' => 'OUT'
	],
	'arrHrWorkCredit' => [
		'1' => 'For Offset',
		'2' => 'For Payroll'
	],
	'arrHrPaymentTerm' => [
		'1' => 'Daily',
		'3' => 'Monthly'
	],
	'arrHrAccrualType' => [
		'1' => 'Non Accruing',
		'2' => 'Monthly',
		'3' => 'Annual'
	],
	'arrChangeSchedulestatus' => [
		'0' => 'Draft',
		'1' => 'Cancelled',
		'2' => 'Disapproved',
		'3' => 'Submitted',
		'4' => 'Pending',
		'5' => 'For Approval',
		'6' => 'Approved',
	],
	'arrApplicableDept' =>[
		'0'=>'',
		'1'=>'Business Permit',
		'2'=>'Real Property',
		'3'=>'Engineering',
		'4'=>'Occupancy',
		'5'=>'Planning and Development',
		'6'=>'Health & Safety',
		'7'=>'Community Tax',
		'8'=>'Burial Permit',
		'9'=>'Miscellaneous'
	],
	'arrOTMultiplier' =>[
		// null=>'Select Multiplier',
		'ordinary'=>1.25,//Ordinary day
		'rest_day'=>1.69,//Rest Day
		'special_holiday'=>1.69,//Special Holiday
		'special_holiday_rest_day'=>1.95,//Special Holiyday + Rest Day
		'regular_holiday'=>2.6,//Regular Holiday
		'regular_holiday_rest_day'=>3.38,//Regular Holiday + Rest Day
		'double_holiday'=>3.9,//Double Holiday
		'double_holiday_rest_day'=>5.07,//Double Holiday + Rest Day
	],
	'arrWorkMultiplier' =>[
		0=>'Ordinary day',
		'0.3'=>'Special Holiday',
		1=>'Regular Holiday',
		2=>'Double Holiyday',
	],
	// hr deduction list
	'hrDeductions' =>[
		'gsis_contribution' => 'hrpr_deduction',
		'pag_ibig' => 'hrpr_deduction',
		'philhealth' => 'hrpr_deduction',
		'gsis_conso' => 'hrpr_deduction',
		'gsis_educ' => 'hrpr_deduction',
		'gsis_emergency' => 'hrpr_deduction',
		'cash_loan' => 'hrpr_deduction',
		'tax' => 'hrpr_deduction',
		'policy_loan' => 'hrpr_deduction',
		'pagibig_loan' => 'hrpr_deduction',
		'gsis_add_prem' => 'hrpr_deduction',
		'coop_loan' => 'hrpr_deduction',
		'gsis_multipurpose' => 'hrpr_deduction',
		'computer_loan' => 'hrpr_deduction',
		'pagibig_calamity' => 'hrpr_deduction',
		'gsis_loan' => 'hrpr_deduction',
		'coop_lwop' => 'hrpr_deduction',
		'gsis_gs' => 'hrpr_gov_share',
		'pagibig_gs' => 'hrpr_gov_share',
		'philhealth_gs' => 'hrpr_gov_share',
		'ecc' => 'hrpr_gov_share',
	],
	'hrSettings' => [
		'work_days' => 20
	],
	//Leave Others
	'hrIncaseOther' => [
		1 => [
			'value'=>1,
			'name'=>'Monetization of Leave Credits',
			'shortcode'=>'monetization'
		],
		2 => [
			'value'=>2,
			'name'=>'Terminal Leave',
			'shortcode'=>'terminal'
		],
	],
	// HR Employee education background
	'hrEducationBackground' =>[
		'elem'=>'Elementary',
		'hs'=>'Secondary',
		'voc'=>'Vocational Course / Trade Course',
		'college'=>'College',
		'grad'=>'Graduate Studies',
	],
	'paymentTerms' =>[
		'0'=>'',
		'1'=>'Cash',
		'2'=>'Bank',
		'3'=>'Cheque',
		'4'=>'Credit Card',
		'5'=>'Online',
	],
	'orTypeIds' =>[
		'real_property' => 5, // Accountable Form No. 56
	],
	'bploPaymentStatus' =>[
		'0' => 'Pending',
		'1' => 'Paid',
		'2' => 'Cancelled'
	],
	'med_cert_type' =>[ 
		'1'=> 'Physical Health',
   		'2'=> 'Medico Legal',
   		'3'=> 'Mental Health'
	],
	'accReceiveSetupCategory' =>[ 
		'1'=> 'Basic Tax',
   		'2'=> 'Special Education Tax',
   		'3'=> 'Socialize Housing Tax'
	],
	
	'version'=>'1.0',
	'sub_domain'=>'/dev',
	'remoteserverurl'=>'http://194.163.142.192/palayan_backend/public/uploads'
];

