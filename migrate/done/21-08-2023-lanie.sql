ALTER TABLE `hr_biometrics_record` ADD COLUMN IF NOT EXISTS `is_copied` int not null DEFAULT 0 COMMENT 'if copied to timecard';
ALTER TABLE `hr_missed_log` ADD COLUMN IF NOT EXISTS `is_copied` int not null DEFAULT 0 COMMENT 'if copied to timecard';
