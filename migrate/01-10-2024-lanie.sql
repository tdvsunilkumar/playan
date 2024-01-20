ALTER TABLE `hr_employees` 
ADD COLUMN IF NOT EXISTS `hr_emp_birth_place` VARCHAR(50) NULL AFTER `philhealth_no`, 
ADD COLUMN IF NOT EXISTS `hr_emp_civil_status` INT NULL  COMMENT 'ref: constants.citCivilStatus' AFTER `hr_emp_birth_place`, 
ADD COLUMN IF NOT EXISTS `hr_emp_citizenship` TINYINT(1) NULL  COMMENT 'Filipino, Dual' AFTER `hr_emp_civil_status`,
ADD COLUMN IF NOT EXISTS `hr_emp_if_dual` TINYINT(1) NULL  COMMENT 'By Birth, By Naturalization'AFTER `hr_emp_citizenship`,
ADD COLUMN IF NOT EXISTS `hr_emp_if_dual_country` VARCHAR(50) NULL AFTER `hr_emp_if_dual`,
ADD COLUMN IF NOT EXISTS `hr_emp_height` VARCHAR(50) NULL AFTER `hr_emp_if_dual_country`,
ADD COLUMN IF NOT EXISTS `hr_emp_weight` VARCHAR(50) NULL AFTER `hr_emp_height`,
ADD COLUMN IF NOT EXISTS `hr_emp_blood_type` VARCHAR(50) NULL AFTER `hr_emp_weight`,
ADD COLUMN IF NOT EXISTS `hr_emp_gsis_no` VARCHAR(50) NULL AFTER `hr_emp_blood_type`,
ADD COLUMN IF NOT EXISTS `hr_emp_agency_emp_no` VARCHAR(50) NULL AFTER `hr_emp_gsis_no`,
ADD COLUMN IF NOT EXISTS `hr_emp_is_same_permanent` TINYINT(1) NULL AFTER `hr_emp_agency_emp_no`,
ADD COLUMN IF NOT EXISTS `hr_emp_house_lot_no_permanent` VARCHAR(50) NULL AFTER `hr_emp_is_same_permanent`,
ADD COLUMN IF NOT EXISTS `hr_emp_street_name_permanent` VARCHAR(50) NULL AFTER `hr_emp_house_lot_no_permanent`,
ADD COLUMN IF NOT EXISTS `hr_emp_subdivision_permanent` VARCHAR(50) NULL AFTER `hr_emp_street_name_permanent`,
ADD COLUMN IF NOT EXISTS `hr_emp_brgy_code_permanent` INT NULL  COMMENT 'ref-Table: barangays.brgy_id' AFTER `hr_emp_subdivision_permanent`,
ADD COLUMN IF NOT EXISTS `hr_emp_city_code_permanent` INT NULL  COMMENT 'ref-Table: profile_municipalities.id' AFTER `hr_emp_brgy_code_permanent`,
ADD COLUMN IF NOT EXISTS `hr_emp_province_code_permanent` INT NULL  COMMENT 'ref-Table: profile_provinces.id' AFTER `hr_emp_city_code_permanent`,
ADD COLUMN IF NOT EXISTS `hr_emp_zip_code_permanent` VARCHAR(50) NULL AFTER `hr_emp_province_code_permanent`,
ADD COLUMN IF NOT EXISTS `hr_emp_complete_address_permanent` VARCHAR(225) NULL AFTER `hr_emp_zip_code_permanent`;


CREATE TABLE `hr_emp_family_bg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hrefb_spouse_first_name` VARCHAR(225) NULL,
  `hrefb_spouse_middle_name` VARCHAR(225) NULL,
  `hrefb_spouse_last_name` VARCHAR(225) NULL,
  `hrefb_spouse_suffix` VARCHAR(225) NULL,
  `hrefb_spouse_occupation` VARCHAR(225) NULL,
  `hrefb_spouse_employee_business` VARCHAR(225) NULL,
  `hrefb_spouse_employee_business_address` VARCHAR(225) NULL,
  `hrefb_spouse_mobile_no` VARCHAR(225) NULL,
  `hrefb_spouse_telephone_no` VARCHAR(225) NULL,
  `hrefb_father_last_name` VARCHAR(225)  NULL,
  `hrefb_father_first_name` VARCHAR(225)  NULL,
  `hrefb_father_middle_name` VARCHAR(225) NULL,
  `hrefb_father_suffix` VARCHAR(225) NULL,
  `hrefb_mother_last_name` VARCHAR(225)  NULL,
  `hrefb_mother_first_name` VARCHAR(225)  NULL,
  `hrefb_mother_middle_name` VARCHAR(225) NULL,
  `hrefb_mother_suffix` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_childrens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hrec_first_name` VARCHAR(225) NULL,
  `hrec_middle_name` VARCHAR(225) NULL,
  `hrec_last_name` VARCHAR(225) NULL,
  `hrec_suffix` VARCHAR(225) NULL,
  `hrec_date_of_birth` date NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_educ` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hree_level` date NULL,
  `hree_school` VARCHAR(225) NULL,
  `hree_degree` VARCHAR(50) NULL,
  `hree_period_from` VARCHAR(225) NULL,
  `hree_period_to` VARCHAR(225) NULL,
  `hree_units` VARCHAR(225) NULL,
  `hree_year_grad` VARCHAR(225) NULL,
  `hree_scholarship` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_civil_service_eligibility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hrecse_service` VARCHAR(225) NULL,
  `hrecse_rating` VARCHAR(225) NULL,
  `hrecse_date_of_exam` date NULL,
  `hrecse_place_of_exam` VARCHAR(225) NULL,
  `hrecse_number` VARCHAR(225) NULL,
  `hrecse_valid_date` date NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;



CREATE TABLE `hr_emp_work_exps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hrewe_inclusive_from` date NULL,
  `hrewe_inclusive_to` date NULL,
  `hrewe_position_title` VARCHAR(225) NULL,
  `hrewe_company` VARCHAR(225) NULL,
  `hrewe_monthly_salary` VARCHAR(225) NULL,
  `hrewe_salary_grade` VARCHAR(225) NULL,
  `hrewe_appointment_status` VARCHAR(225) NULL,
  `hrewe_gov_service` TINYINT(1) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_voluntary_works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hrevw_org_name` VARCHAR(225) NULL,
  `hrevw_org_address` VARCHAR(225) NULL,
  `hrevw_inclusive_from` date NULL,
  `hrevw_inclusive_to` date NULL,
  `hrevw_hours` VARCHAR(5) NULL,
  `hrevw_position` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_training_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hretp_org_name` VARCHAR(225) NULL,
  `hretp_inclusive_from` date NULL,
  `hretp_inclusive_to` date NULL,
  `hretp_id_type` VARCHAR(5) NULL,
  `hretp_sponsored_by` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_hobbies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hreh_description` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_recognitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hrer_description` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_orgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hreo_description` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hreo_name` VARCHAR(225) NULL,
  `hreo_address` VARCHAR(225) NULL,
  `hreo_contact_no` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

CREATE TABLE `hr_emp_other_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id' ,
  `hreo_question` VARCHAR(225) NULL,
  `hreo_yes_no` boolean NULL,
  `hreo_details` VARCHAR(225) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `created_at` timestamp NOT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;