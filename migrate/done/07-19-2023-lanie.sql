CREATE TABLE `welfare_social_welfare_social_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wswa_id` int NOT NULL COMMENT 'ref-Table: welfare_social_welfare_assistance.wswa_id',
  `wswsc_health_status` VARCHAR(200) NULL,
  `wswsc_problem_presented` text NULL,
  `wswsc_family_background` text NULL,
  `wswsc_diagnostic_impression` text NULL,
  `wswsc_reco` text NULL,
  `wswsc_prepared_by` int(11) NULL COMMENT 'reference hr_employee_id of the system who click the print button of PRINT SOCIAL CASE STUDY',
  `wswsc_approved_by` int(11) NULL COMMENT 'reference hr_employee_id of the system who approve the assistance',
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `updated_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system  who update the details',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. ',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  PRIMARY KEY(id)
) ;

CREATE TABLE `welfare_social_welfare_sc_treatment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wswsc_id` int NOT NULL COMMENT 'ref-Table: welfare_social_welfare_social_case.wswsc_id',
  `wswsc_treatment_plan_objectives` VARCHAR(200) NULL,
  `wswsc_treatment_plan_activities` VARCHAR(200) NULL,
  `wswsc_treatment_plan_strategies` VARCHAR(200) NULL,
  `wswsc_treatment_plan_timeframe` VARCHAR(200) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `updated_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system  who update the details',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. ',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  PRIMARY KEY(id)
) ;

CREATE TABLE `welfare_social_welfare_assistance_request_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wswa_id` int NOT NULL COMMENT 'ref-Table: welfare_social_welfare_assistance.wswa_id',
  `wswart_body` text NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `updated_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system  who update the details',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. ',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  PRIMARY KEY(id)
) ;

CREATE TABLE `welfare_swsc_dependent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wswsc_id` int NOT NULL COMMENT 'ref-Table: welfare_social_welfare_social_case.wswsc_id',
  `wswscd_cit_id` int NOT NULL COMMENT 'ref-Table: citizens.cit_id',
  `wswscd_relation` VARCHAR(200) NULL,
  `wswscd_health_status` VARCHAR(200) NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `updated_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system  who update the details',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. ',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  PRIMARY KEY(id)
) ;

CREATE TABLE `welfare_policy_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wps_key` VARCHAR(200) NOT NULL,
  `wps_value` VARCHAR(200) NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `updated_by` int(11) NOT NULL COMMENT 'reference hr_employee_id of the system  who update the details',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. ',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00',
  PRIMARY KEY(id)
) ;

ALTER TABLE `welfare_spa_family_composition` MODIFY COLUMN IF EXISTS `wsfc_occupation` varchar(255) NULL;
ALTER TABLE `welfare_spa_family_composition` MODIFY COLUMN IF EXISTS `wsfc_monthly_income` decimal(14,3) NULL;

