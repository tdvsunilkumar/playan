ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `is_payables` BOOLEAN NOT NULL DEFAULT TRUE AFTER `is_replenish`;