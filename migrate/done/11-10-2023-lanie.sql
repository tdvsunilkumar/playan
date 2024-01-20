ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS  `days` INT NULL AFTER `hrla_id`;
ALTER TABLE `hr_leaves` CHANGE `days` `days` DOUBLE NULL DEFAULT NULL, CHANGE `dayswithpay` `dayswithpay` DOUBLE NOT NULL DEFAULT '0';
ALTER TABLE `hr_leave_earning_adjustment_detail` CHANGE `hrlpc_days` `hrlpc_days` DOUBLE NULL DEFAULT '0' COMMENT 'get number from Leave Parameter # Of Days', CHANGE `hrlead_used` `hrlead_used` DOUBLE NULL DEFAULT '0' COMMENT 'Used', CHANGE `hrlead_balance` `hrlead_balance` DOUBLE NULL DEFAULT '0' COMMENT 'Balance';
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS  `remainingdays` INT NULL AFTER `dayswithpay`;

ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `top_transaction_type_id` INT NULL AFTER `type_of_transaction_id`;
ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `tfoc_id` INT NULL AFTER `top_transaction_type_id`;
ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `gl_account_id` INT NULL AFTER `tfoc_id`;
ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `sl_account_id` INT NULL AFTER `gl_account_id`;
ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `civil_status` INT NULL AFTER `gender`;
ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `or_no` INT NULL AFTER `penalty`;
ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `or_date` date NULL AFTER `or_no`;
