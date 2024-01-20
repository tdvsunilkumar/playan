UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 457;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 458;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 459;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 475;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 476;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 477;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 289;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 304;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 305;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 306;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 307;
UPDATE `acctg_account_general_ledgers` SET `is_payroll` = '1' WHERE `acctg_account_general_ledgers`.`id` = 459;

ALTER TABLE `eng_bldg_permit_apps` CHANGE `ebpa_issued_date` `ebpa_issued_date` DATETIME NULL COMMENT 'Date Issued date when bldg permit issued';

CREATE TABLE `hr_payroll_breakdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payroll_no` VARCHAR(225) NOT NULL,
  `emp_id` int NOT NULL,
  `hrcp_no` int NOT NULL,
  `gl_id` int NOT NULL,
  `sl_id` int NULL,
  `desc` VARCHAR(225) NULL,
  `amount` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;