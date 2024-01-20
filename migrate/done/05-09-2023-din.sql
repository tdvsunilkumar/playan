ALTER TABLE `gso_property_accountabilities` ADD COLUMN IF NOT EXISTS `effectivity_date` DATE NULL DEFAULT NULL AFTER `estimated_life_span`;

ALTER TABLE `menu_groups` ADD COLUMN IF NOT EXISTS `is_dashboard` BOOLEAN NOT NULL DEFAULT FALSE AFTER `order`;