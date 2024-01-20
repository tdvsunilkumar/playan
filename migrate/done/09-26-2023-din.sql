ALTER TABLE `acctg_incomes` ADD COLUMN IF NOT EXISTS `payee_id` INT NULL DEFAULT NULL COMMENT 'cbo_payee' AFTER `voucher_id`;

ALTER TABLE `acctg_payables` ADD COLUMN IF NOT EXISTS `payee_id` INT NULL DEFAULT NULL COMMENT 'cbo_payee' AFTER `voucher_id`;

ALTER TABLE `acctg_incomes` CHANGE `voucher_id` `voucher_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'acctg_vouchers';

ALTER TABLE `eco_rental_application` ADD COLUMN IF NOT EXISTS `remaining_amount` DOUBLE NULL DEFAULT NULL AFTER `total_amount`;

ALTER TABLE `eco_rental_application` ADD COLUMN IF NOT EXISTS `top_transaction_id` INT NULL DEFAULT NULL AFTER `remaining_amount`;

ALTER TABLE `acctg_incomes` CHANGE `gl_account_id` `gl_account_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'acctg_account_general_ledgers', CHANGE `sl_account_id` `sl_account_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'acctg_account_subsidiary_ledgers';

ALTER TABLE `acctg_account_general_ledgers` ADD COLUMN IF NOT EXISTS `is_treasury` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_payable`;

UPDATE `acctg_account_general_ledgers` SET `is_treasury` = '1' WHERE `acctg_account_general_ledgers`.`id` = 1;