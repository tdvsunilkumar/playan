ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS  `department_id` INT NULL AFTER `hr_employeesid`;
ALTER TABLE `hr_offsets` ADD COLUMN IF NOT EXISTS  `department_id` INT NULL AFTER `hr_employeesid`;