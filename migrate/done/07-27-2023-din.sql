ALTER TABLE `gso_pre_repair_inspection_history` ADD COLUMN IF NOT EXISTS `property_id` INT NOT NULL COMMENT 'gso_property_accountabilities' AFTER `id`;

ALTER TABLE `gso_property_accountabilities` ADD COLUMN IF NOT EXISTS `is_departmental` BOOLEAN NOT NULL DEFAULT TRUE AFTER `is_depreciative`;

ALTER TABLE `gso_property_accountabilities` ADD COLUMN IF NOT EXISTS `is_locked` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_departmental`;