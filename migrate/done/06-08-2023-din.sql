ALTER TABLE `gso_issuances_types` ADD COLUMN IF NOT EXISTS `is_property_asset` BOOLEAN NOT NULL DEFAULT FALSE AFTER `descrption`;
UPDATE `gso_issuances_types` SET `is_property_asset` = '1' WHERE `gso_issuances_types`.`id` = 2;
UPDATE `gso_issuances_types` SET `is_property_asset` = '1' WHERE `gso_issuances_types`.`id` = 3;

