ALTER TABLE `profile_municipalities` ADD COLUMN IF NOT EXISTS `uacs_code` INT NULL AFTER `mun_display_for_welfare`;
ALTER TABLE `barangays` ADD COLUMN IF NOT EXISTS `uacs_code` INT NULL AFTER `brgy_display_for_rpt_locgroup`;
