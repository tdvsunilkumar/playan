ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `trigger_type` varchar(10) NOT NULL AFTER `type_of_transaction`;
ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `trigger_count` int NOT NULL AFTER `type_of_transaction`;
ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `computation` text NOT NULL AFTER `type_of_transaction`;
ALTER TABLE `eco_type_of_transaction` ADD COLUMN IF NOT EXISTS `penalties` text NOT NULL AFTER `type_of_transaction`;

ALTER TABLE `eco_housing_application` CHANGE `next_trigger` `next_trigger` DATE NULL;

ALTER TABLE `citizens` CHANGE `cit_middle_name` `cit_middle_name` VARCHAR(255) NULL;
ALTER TABLE `eco_residential_name` 
    DROP `trigger_type`,
    DROP `trigger_count`,
    DROP `computation`,
    DROP `is_month`;