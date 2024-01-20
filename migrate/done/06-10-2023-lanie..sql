ALTER TABLE `hr_payroll` ADD COLUMN IF NOT EXISTS  `hrpr_gov_share` TEXT NULL AFTER `hrpr_deduction`;
ALTER TABLE `hr_gsis_table` ADD COLUMN IF NOT EXISTS  `hrgt_gov_share` DOUBLE NULL AFTER `hrgt_amount_to`;
ALTER TABLE `hr_gsis_table` ADD COLUMN IF NOT EXISTS  `hrgt_personal_share` DOUBLE NULL AFTER `hrgt_amount_to`;
ALTER TABLE `hr_gsis_table` ADD COLUMN IF NOT EXISTS  `hrgt_gov_type` int NULL COMMENT '0 = percent, 1 = fixed' AFTER `hrgt_amount_to` ;
ALTER TABLE `hr_gsis_table` ADD COLUMN IF NOT EXISTS  `hrgt_personal_type` int NULL COMMENT '0 = percent, 1 = fixed' AFTER `hrgt_amount_to` ;
ALTER TABLE hr_gsis_table DROP COLUMN IF EXISTS hrgt_percentage;

ALTER TABLE `hr_pagibig_table` ADD COLUMN IF NOT EXISTS  `hrpit_gov_share` DOUBLE NULL AFTER `hrpit_amount_to`;
ALTER TABLE `hr_pagibig_table` ADD COLUMN IF NOT EXISTS  `hrpit_personal_share` DOUBLE NULL AFTER `hrpit_amount_to`;
ALTER TABLE `hr_pagibig_table` ADD COLUMN IF NOT EXISTS  `hrpit_gov_type` int NULL COMMENT '0 = percent, 1 = fixed' AFTER `hrpit_amount_to` ;
ALTER TABLE `hr_pagibig_table` ADD COLUMN IF NOT EXISTS  `hrpit_personal_type` int NULL COMMENT '0 = percent, 1 = fixed' AFTER `hrpit_amount_to` ;
ALTER TABLE hr_pagibig_table DROP COLUMN IF EXISTS hrpit_percentage;

ALTER TABLE `hr_phil_healths` ADD COLUMN IF NOT EXISTS  `hrpt_gov_share` DOUBLE NULL AFTER `hrpt_amount_to`;
ALTER TABLE `hr_phil_healths` ADD COLUMN IF NOT EXISTS  `hrpt_personal_share` DOUBLE NULL AFTER `hrpt_amount_to`;
ALTER TABLE `hr_phil_healths` ADD COLUMN IF NOT EXISTS  `hrpt_gov_type` int NULL COMMENT '0 = percent, 1 = fixed' AFTER `hrpt_amount_to` ;
ALTER TABLE `hr_phil_healths` ADD COLUMN IF NOT EXISTS  `hrpt_personal_type` int NULL COMMENT '0 = percent, 1 = fixed' AFTER `hrpt_amount_to` ;
ALTER TABLE hr_phil_healths DROP COLUMN IF EXISTS hrpt_percentage;

ALTER TABLE `hr_leave_parameter_detail` CHANGE `hrlpc_days` `hrlpc_days` INT(11) NULL DEFAULT '0' COMMENT '# Of Days', CHANGE `hrlpc_credits` `hrlpc_credits` INT(11) NULL DEFAULT '0' COMMENT 'Accrual Credits';