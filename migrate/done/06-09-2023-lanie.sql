ALTER TABLE `hr_overtime` CHANGE `hro_reason` `hro_reason` VARCHAR(225) NOT NULL ;
ALTER TABLE `hr_overtime` ADD `hrot_is_process` INT NOT NULL DEFAULT '0' AFTER `updated_at`;
ALTER TABLE `hr_overtime` CHANGE `hrot_ot_cost` `hrot_ot_cost` decimal(10,2) NULL DEFAULT NULL COMMENT 'hr_appointment.hra_hourly_rate * hr_overtime.hrot_multiplier * hr_overtime.hrot_considered_hours';