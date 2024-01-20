ALTER TABLE `hr_timecards` ADD COLUMN IF NOT EXISTS `hrht_id` int NULL AFTER `hrtc_ot`;
ALTER TABLE `hr_timecards` ADD COLUMN IF NOT EXISTS `hrtc_multipier` decimal(2,2) NULL AFTER `hrht_id`;

ALTER TABLE `hr_holiday_types` ADD COLUMN IF NOT EXISTS `hrht_multipier` decimal(2,2) NULL AFTER `hrht_description`;

ALTER TABLE `hr_holidays` ADD COLUMN IF NOT EXISTS `hrh_is_paid` int NULL AFTER `hrht_id`;
