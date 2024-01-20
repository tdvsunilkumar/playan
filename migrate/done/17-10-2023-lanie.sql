ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `monthly_pay` DOUBLE NULL AFTER `initial_monthly`;
ALTER TABLE `eco_housing_application` ADD COLUMN IF NOT EXISTS `next_penalty_date` date NULL AFTER `penalty`;

ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `trigger_type` varchar(10) NOT NULL AFTER `type_of_transaction`;
ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `trigger_count` int NOT NULL AFTER `type_of_transaction`;
ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `computation` text NOT NULL AFTER `type_of_transaction`;
ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `penalties` int NOT NULL AFTER `type_of_transaction`;

ALTER TABLE `bfp_fees_masters` ADD COLUMN IF NOT EXISTS `fmaster_shortname` VARCHAR(255) NOT NULL AFTER `fmaster_description`;