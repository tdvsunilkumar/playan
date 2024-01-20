ALTER TABLE `profile_provinces` ADD COLUMN IF NOT EXISTS `uacs_code` INT NULL AFTER `prov_desc`;
ALTER TABLE `profile_regions` ADD COLUMN IF NOT EXISTS `uacs_code` INT NULL AFTER `reg_description`;