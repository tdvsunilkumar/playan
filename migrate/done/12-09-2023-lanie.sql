ALTER TABLE `hr_phil_healths` CHANGE `hrpt_percentage` `hrpt_percentage` DOUBLE NOT NULL COMMENT 'Percentage';
ALTER TABLE `hr_phil_healths` CHANGE `hrpt_amount_from` `hrpt_amount_from` DECIMAL(10,5) NOT NULL COMMENT 'From', CHANGE `hrpt_amount_to` `hrpt_amount_to` DECIMAL(10,5) NOT NULL COMMENT 'To';
ALTER TABLE `hr_tax_table` CHANGE `hrtt_percentage` `hrtt_percentage` DOUBLE NOT NULL;
ALTER TABLE `hr_pagibig_table` CHANGE `hrpit_amount_from` `hrpit_amount_from` DECIMAL(10,2) NOT NULL, CHANGE `hrpit_amount_to` `hrpit_amount_to` DECIMAL(10,2) NOT NULL;
ALTER TABLE `hr_gsis_table` CHANGE `hrgt_amount_from` `hrgt_amount_from` DECIMAL(10,5) NOT NULL, CHANGE `hrgt_amount_to` `hrgt_amount_to` DECIMAL(10,5) NOT NULL, CHANGE `hrgt_percentage` `hrgt_percentage` DOUBLE NOT NULL;
ALTER TABLE `hr_tax_table` ADD `hrtt_fixed_amount` DECIMAL(10,3) NOT NULL AFTER `hrtt_percentage`;
ALTER TABLE `hr_income_and_deduction` CHANGE `hriad_amount` `hriad_amount` DECIMAL(10,5) NOT NULL;
ALTER TABLE `ho_fam_plan` CHANGE `fam_ref_id` `fam_ref_id` VARCHAR(225) NOT NULL COMMENT 'Application No.';