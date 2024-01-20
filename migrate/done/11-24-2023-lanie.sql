CREATE TABLE `hr_system_policy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hrsp_description` VARCHAR(225) NOT NULL,
  `hrsp_code` VARCHAR(50) NOT NULL,
  `hrsp_matrix` VARCHAR(225) NOT NULL,
  `hrsp_value` VARCHAR(11) NOT NULL,
  `hrsp_note` VARCHAR(225) NULL,
  `hrsp_slug` VARCHAR(225) NULL,
  `hrsp_is_active` int(11) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY(id)
) ;

INSERT INTO `hr_system_policy` 
(`id`, `hrsp_description`, `hrsp_matrix`, `hrsp_value`, `hrsp_note`, `hrsp_slug`, `hrsp_is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `hrsp_code`) 
VALUES 
(NULL, 'Work Days', 'hours', '', 'Working days computation', 'hr/policy/work-days', '1', '1', current_timestamp(), NULL, NULL,'work_days');

INSERT INTO `hr_system_policy` 
(`id`, `hrsp_description`, `hrsp_matrix`, `hrsp_value`, `hrsp_note`, `hrsp_slug`, `hrsp_is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `hrsp_code`) 
VALUES 
(NULL, 'Leave Description', 'boolean', 'Yes', 'For Leave deduction', 'hr/policy/leave-deduction', '1', '1', current_timestamp(), NULL, NULL, 'leave_deduction');