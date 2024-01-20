ALTER TABLE `hr_income_and_deduction` ADD COLUMN IF NOT EXISTS `hridt_type` INT NULL COMMENT "0 = deduction, 1 = income, 2 = gov share"  AFTER `hridt_id`;
