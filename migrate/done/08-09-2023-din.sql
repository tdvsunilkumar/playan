ALTER TABLE `menu_groups` ADD COLUMN IF NOT EXISTS `form_name` TEXT NULL DEFAULT NULL AFTER `description`;

ALTER TABLE `menu_modules` ADD COLUMN IF NOT EXISTS `form_name` TEXT NULL DEFAULT NULL AFTER `description`;