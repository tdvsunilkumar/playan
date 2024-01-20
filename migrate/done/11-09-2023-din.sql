ALTER TABLE `acctg_account_general_ledgers` ADD COLUMN IF NOT EXISTS `is_cash_advanced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_petty_cash`;

UPDATE `acctg_account_general_ledgers` SET `is_cash_advanced` = '1' WHERE `acctg_account_general_ledgers`.`code` = '10305020';