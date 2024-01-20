ALTER TABLE `acctg_account_subsidiary_ledgers` ADD COLUMN IF NOT EXISTS `is_rpt_tax_cy` BOOLEAN NOT NULL DEFAULT FALSE AFTER `sl_parent_id`;

