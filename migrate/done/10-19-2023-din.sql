ALTER TABLE `gso_purchase_requests` ADD COLUMN IF NOT EXISTS `approved_counter` INT NOT NULL DEFAULT '1' AFTER `sent_by`;
ALTER TABLE `gso_purchase_requests` ADD COLUMN IF NOT EXISTS `approved_datetime` TEXT NULL DEFAULT NULL AFTER `approved_counter`;

ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `collection_voucher_approver` INT NULL DEFAULT NULL AFTER `collection_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `collection_voucher_prepared` INT NULL DEFAULT NULL AFTER `collection_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `payables_voucher_approver` INT NULL DEFAULT NULL AFTER `payables_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `payables_voucher_prepared` INT NULL DEFAULT NULL AFTER `payables_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `cash_voucher_approver` INT NULL DEFAULT NULL AFTER `cash_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `cash_voucher_prepared` INT NULL DEFAULT NULL AFTER `cash_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `cheque_voucher_approver` INT NULL DEFAULT NULL AFTER `cheque_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `cheque_voucher_prepared` INT NULL DEFAULT NULL AFTER `cheque_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `others_voucher_approver` INT NULL DEFAULT NULL AFTER `others_voucher_date`;
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `others_voucher_prepared` INT NULL DEFAULT NULL AFTER `others_voucher_date`;