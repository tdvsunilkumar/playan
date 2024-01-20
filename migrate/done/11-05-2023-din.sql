ALTER TABLE `acctg_payables` ADD IF NOT EXISTS `posted_at` TIMESTAMP NULL DEFAULT NULL AFTER `disapproved_remarks`, ADD IF NOT EXISTS `posted_by` INT NULL DEFAULT NULL AFTER `posted_at`;
ALTER TABLE `acctg_incomes` ADD IF NOT EXISTS `posted_at` TIMESTAMP NULL DEFAULT NULL AFTER `disapproved_remarks`, ADD IF NOT EXISTS `posted_by` INT NULL DEFAULT NULL AFTER `posted_at`;
ALTER TABLE `acctg_disbursements` ADD IF NOT EXISTS `posted_at` TIMESTAMP NULL DEFAULT NULL AFTER `disapproved_remarks`, ADD IF NOT EXISTS `posted_by` INT NULL DEFAULT NULL AFTER `posted_at`;
ALTER TABLE `acctg_deductions` ADD IF NOT EXISTS `posted_at` TIMESTAMP NULL DEFAULT NULL AFTER `disapproved_remarks`, ADD IF NOT EXISTS `posted_by` INT NULL DEFAULT NULL AFTER `posted_at`;
