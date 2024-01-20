ALTER TABLE `acctg_vouchers_series` CHANGE `fund_code_id` `fund_code_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'acctg_fund_codes';

ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `is_replenish` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`;

ALTER TABLE `cbo_allotment_obligations` ADD COLUMN IF NOT EXISTS `is_attached` BOOLEAN NOT NULL DEFAULT FALSE AFTER `pr_status`;

ALTER TABLE `acctg_account_general_ledgers` ADD COLUMN IF NOT EXISTS `is_petty_cash` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_due_to_bir`;

UPDATE `acctg_account_general_ledgers` SET `is_petty_cash` = '1' WHERE `acctg_account_general_ledgers`.`id` = 2;

ALTER TABLE `gso_unit_of_measurements` ADD COLUMN IF NOT EXISTS `is_lot` BOOLEAN NOT NULL DEFAULT FALSE AFTER `remarks`;