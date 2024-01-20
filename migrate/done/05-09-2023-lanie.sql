ALTER TABLE `hr_overtime` ADD COLUMN IF NOT EXISTS `hrot_is_regular_day` INT DEFAULT 0 COMMENT 'Yes = 1, No = 0' AFTER `hrot_considered_hours`;
ALTER TABLE `hr_overtime` ADD COLUMN IF NOT EXISTS `hrot_is_regular_holiday` INT DEFAULT 0 COMMENT 'Yes = 1, No = 0' AFTER `hrot_considered_hours`;
ALTER TABLE `hr_overtime` ADD COLUMN IF NOT EXISTS `hrot_is_special_holiday` INT DEFAULT 0 COMMENT 'Yes = 1, No = 0' AFTER `hrot_considered_hours`;
ALTER TABLE `hr_overtime` ADD COLUMN IF NOT EXISTS `hrot_is_double_holiday` INT DEFAULT 0 COMMENT 'Yes = 1, No = 0' AFTER `hrot_considered_hours`;
ALTER TABLE `hr_overtime` ADD COLUMN IF NOT EXISTS `hrot_is_rest_day` INT DEFAULT 0 COMMENT 'Yes = 1, No = 0' AFTER `hrot_considered_hours`;
