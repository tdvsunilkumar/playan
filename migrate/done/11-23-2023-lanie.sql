ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrl_incase_vl_sp_speficy_remarks` VARCHAR(225) NULL AFTER `hrla_reason`;
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrl_incase_sl_specify_remarks` VARCHAR(225) NULL AFTER `hrla_reason`;
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrl_incase_special_leave_women_remarks` VARCHAR(225) NULL AFTER `hrla_reason`;
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrl_incase_vl_special_privilege` INT NULL AFTER `hrl_incase_vl_sp_speficy_remarks`;
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrl_incase_sl` INT NULL AFTER `hrl_incase_vl_sp_speficy_remarks`;
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrl_incase_study_leave` INT NULL AFTER `hrl_incase_vl_sp_speficy_remarks`;
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrl_incase_others` VARCHAR(225) NULL AFTER `hrl_incase_vl_sp_speficy_remarks`;
ALTER TABLE `hr_leaves` ADD COLUMN IF NOT EXISTS `hrla_disapproved_remarks` VARCHAR(225) NULL AFTER `hrla_disapproved_at`;