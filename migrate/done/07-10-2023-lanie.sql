ALTER TABLE `acctg_account_general_ledgers` ADD COLUMN IF NOT EXISTS `is_payroll` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_treasury`;
ALTER TABLE `hr_employees` ADD COLUMN IF NOT EXISTS  `is_various` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_dept_restricted`;
ALTER TABLE `hr_payroll` ADD COLUMN IF NOT EXISTS  `ewt_id` INT NULL DEFAULT NULL AFTER `hrcp_id`;
ALTER TABLE `hr_payroll` ADD COLUMN IF NOT EXISTS `hrpr_monthly_tax` DOUBLE NULL AFTER `hrpr_deduction`;
ALTER TABLE `hr_income_and_deduction` CHANGE `hriad_amount` `hriad_amount` DECIMAL(10,5) NULL;
CREATE TABLE `cbo_obligation_payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `allotment_id` int(11) NOT NULL,
  `payroll_no` int(11) NOT NULL COMMENT 'ref-table: hr_payroll.hrpr_payroll_no',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;