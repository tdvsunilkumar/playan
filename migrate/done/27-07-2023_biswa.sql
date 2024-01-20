ALTER TABLE `hr_pagibig_table` ADD `is_active` INT(11) NOT NULL DEFAULT '0' AFTER `hrpit_percentage`;
ALTER TABLE `hr_appointment` ADD `is_active` INT(11) NOT NULL DEFAULT '0' AFTER `hra_annual_rate`;
