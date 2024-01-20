ALTER TABLE `hr_payroll` ADD COLUMN IF NOT EXISTS `hrpr_ots` TEXT NULL AFTER `hrpr_net_salary`;
ALTER TABLE `hr_payroll` ADD COLUMN IF NOT EXISTS `hrpr_deduction` TEXT NULL AFTER `hrpr_net_salary`;
ALTER TABLE `hr_payroll` ADD COLUMN IF NOT EXISTS `hrpr_income` TEXT NULL AFTER `hrpr_net_salary`;
ALTER TABLE `hr_income_and_deduction` ADD COLUMN IF NOT EXISTS `hriad_deduct` TEXT NULL AFTER `hriad_balance`;
ALTER TABLE `hr_income_and_deduction` ADD COLUMN IF NOT EXISTS `hriad_date_completed` TEXT NULL AFTER `hriad_balance`;