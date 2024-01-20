ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_sponsor_age` int NULL AFTER `wtcm_sponsor_contact`;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_sponsor_occupation` VARCHAR(200) NULL AFTER `wtcm_sponsor_contact`;
ALTER TABLE `welfare_travel_clearance_minor` MODIFY COLUMN IF EXISTS `wtcm_travel_purpose` text NULL;
