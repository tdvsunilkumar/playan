ALTER TABLE `gso_pre_repair_inspection_requests` ADD COLUMN IF NOT EXISTS `checked_counter` INT NOT NULL DEFAULT '1' AFTER `inspected_remarks`;

ALTER TABLE `gso_property_accountabilities` ADD COLUMN IF NOT EXISTS `fund_code_id` INT NOT NULL DEFAULT '1' COMMENT 'acctg_fund_codes' AFTER `property_type_id`;