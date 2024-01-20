ALTER TABLE `hr_changeof_schedules` CHANGE `id` `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `hr_overtime` ADD `hrot_multiplier` decimal(2,2)  NULL AFTER `hrot_ot_cost`;
ALTER TABLE `hr_timecards` 
    ADD COLUMN IF NOT EXISTS  `hrds_id` INT NULL COMMENT 'ref-Table: hr_default_schedule' AFTER `	hrtc_time_out`, 
    ADD COLUMN IF NOT EXISTS  `hrtc_late` DECIMAL(2,2) NULL COMMENT 'hrds_start_time - hrtc_time_in' AFTER `hrds_id`, 
    ADD COLUMN IF NOT EXISTS  `hrtc_undertime` DECIMAL NULL COMMENT 'hrtc_time_out - hrds_end_time' AFTER `hrtc_late`, 
    ADD COLUMN IF NOT EXISTS  `hrtc_ot` DECIMAL NULL COMMENT 'hrds_end_time - hrtc_time_out ' AFTER `hrtc_undertime`, 
    ADD COLUMN IF NOT EXISTS  `hrht_id` INT NULL COMMENT 'ref-table: hr_holiday_types' AFTER `hrtc_ot`, 
    ADD COLUMN IF NOT EXISTS  `hrtc_multiplier` DECIMAL(2,2) NULL AFTER `hrht_id`;