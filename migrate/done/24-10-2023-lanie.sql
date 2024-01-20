ALTER TABLE `cbo_obligation_payroll` ADD COLUMN IF NOT EXISTS `cutoff_id` INT NOT NULL AFTER `payroll_no`;

ALTER TABLE `hr_appointment_status` ADD COLUMN IF NOT EXISTS `sl_id` INT NULL AFTER `hras_description`;
ALTER TABLE `hr_appointment_status` ADD COLUMN IF NOT EXISTS `gl_id` INT NULL AFTER `hras_description`;

ALTER TABLE `hr_income_deduction_type` ADD COLUMN IF NOT EXISTS `sl_id` INT NULL AFTER `hridt_description`;
ALTER TABLE `hr_income_deduction_type` ADD COLUMN IF NOT EXISTS `gl_id` INT NULL AFTER `hridt_description`;
ALTER TABLE `hr_income_deduction_type` ADD COLUMN IF NOT EXISTS `hridt_type` INT NULL COMMENT "0 = deduction, 1 = income, 2 = gov share" AFTER `hridt_description`;
ALTER TABLE `hr_loan_types` ADD COLUMN IF NOT EXISTS `sl_id` INT NULL AFTER `hrlt_code`;
ALTER TABLE `hr_loan_types` ADD COLUMN IF NOT EXISTS `gl_id` INT NULL AFTER `hrlt_code`;

ALTER TABLE `hr_tax_table` ADD COLUMN IF NOT EXISTS `ewt_id` INT NOT NULL AFTER `hrtt_fixed_amount`;

ALTER TABLE `hr_income_and_deduction` ADD COLUMN IF NOT EXISTS `sl_id` INT NULL AFTER `hriad_ref_no`;
ALTER TABLE `hr_loan_ledger` ADD COLUMN IF NOT EXISTS `sl_id` INT NULL AFTER `hrla_id`;
ALTER TABLE `hr_income_and_deduction` ADD COLUMN IF NOT EXISTS `gl_id` INT NULL AFTER `hriad_ref_no`;
ALTER TABLE `hr_loan_ledger` ADD COLUMN IF NOT EXISTS `gl_id` INT NULL AFTER `hrla_id`;


ALTER TABLE `hr_loan_applications` CHANGE `hrla_loan_amount` `hrla_loan_amount` DOUBLE NOT NULL COMMENT 'Loan Amount', CHANGE `hrla_interest_percentage` `hrla_interest_percentage` DOUBLE NOT NULL COMMENT 'Interest Percentage', CHANGE `hrla_interest_amount` `hrla_interest_amount` DOUBLE NOT NULL COMMENT 'Interest Amount', CHANGE `hrla_amount_disbursed` `hrla_amount_disbursed` DOUBLE NULL DEFAULT NULL COMMENT 'Amount Disbursed', CHANGE `hrla_installment_amount` `hrla_installment_amount` DOUBLE NOT NULL COMMENT 'Installment Amount';