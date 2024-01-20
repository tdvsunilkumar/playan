ALTER TABLE `hr_timecards` CHANGE `hrtc_undertime` `hrtc_undertime` DECIMAL(2,2) NULL DEFAULT NULL, CHANGE `hrtc_late` `hrtc_late` DECIMAL(2,2) NULL DEFAULT NULL, CHANGE `hrtc_ot` `hrtc_ot` DECIMAL(2,2) NULL DEFAULT NULL;
ALTER TABLE `hr_tax_table` CHANGE `hrtt_amount_from` `hrtt_amount_from` DECIMAL(10,2) NOT NULL, CHANGE `hrtt_amount_to` `hrtt_amount_to` DECIMAL(10,2) NOT NULL;

ALTER TABLE `hr_timecards` ADD `hrtc_hours_work` DECIMAL(10,3) NOT NULL AFTER `hrtc_late`;
ALTER TABLE `hr_timekeeping` ADD `hrcp_id` INT NOT NULL AFTER `hrtk_date`;