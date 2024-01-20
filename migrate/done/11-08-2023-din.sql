ALTER TABLE `acctg_payables` ADD COLUMN IF NOT EXISTS `paid_amount` DOUBLE NULL DEFAULT NULL AFTER `total_amount`;
ALTER TABLE `acctg_incomes` ADD COLUMN IF NOT EXISTS `deposited_amount` DOUBLE NULL DEFAULT NULL AFTER `total_amount`;

ALTER TABLE `acctg_debit_memos` ADD COLUMN IF NOT EXISTS `paid_amount` DOUBLE NULL DEFAULT NULL AFTER `total_amount`;
ALTER TABLE `acctg_debit_memos` ADD COLUMN IF NOT EXISTS `posted_at` TIMESTAMP NULL DEFAULT NULL AFTER `disapproved_remarks`, ADD COLUMN IF NOT EXISTS `posted_by` INT NULL DEFAULT NULL AFTER `posted_at`;

ALTER TABLE `acctg_deductions` CHANGE `amount` `amount` DOUBLE(14,2) NULL DEFAULT NULL;
ALTER TABLE `acctg_deductions` CHANGE `total_amount` `total_amount` DOUBLE(14,2) NULL DEFAULT NULL;

ALTER TABLE `acctg_incomes` CHANGE `amount` `amount` DOUBLE(14,2) NULL DEFAULT NULL;
ALTER TABLE `acctg_incomes` CHANGE `total_amount` `total_amount` DOUBLE(14,2) NULL DEFAULT NULL;
ALTER TABLE `acctg_incomes` CHANGE `deposited_amount` `deposited_amount` DOUBLE(14,2) NULL DEFAULT NULL;