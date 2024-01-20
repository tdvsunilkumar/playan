ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_is_approve` INT NULL AFTER `wtcm_approved_by`;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_cashier_id` INT NULL AFTER `wtcm_is_approve`;

ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_cashierd_id` INT NULL AFTER `wtcm_cashier_id`;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `or_no` INT NULL AFTER `wtcm_cashierd_id`;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `or_date` INT NULL AFTER `or_no`;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `or_amount` INT NULL AFTER `or_date`;

ALTER TABLE `welfare_travel_clearance_minor` DROP COLUMN IF EXISTS top_transaction_type_id;
ALTER TABLE `welfare_travel_clearance_minor` DROP COLUMN IF EXISTS transaction_no;
ALTER TABLE `welfare_travel_clearance_minor` DROP COLUMN IF EXISTS wts_id;

DROP TABLE IF EXISTS welfare_tcm_service;