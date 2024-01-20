INSERT INTO `acctg_disburse_types` 
(`code`, `name`, `description`, `is_payroll`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) 
VALUES 
( 'salary', 'Salaries & Wages', 'Salaries & Wages', '1', current_timestamp(), '1', NULL, NULL, '1'), 
( 'Reimbursement', 'Reimbursement', 'Reimbursement', '1', current_timestamp(), '1', NULL, NULL, '1'), 
( 'Pettycash', 'Pettycash', 'Pettycash', '1', current_timestamp(), '1', NULL, NULL, '1'), 
( 'pagibig', 'PAG-IBIG', 'PAG-IBIG', '1', current_timestamp(), '1', NULL, NULL, '1'), 
( 'philhealth', 'PhilHealth', 'PhilHealth', '1', current_timestamp(), '1', NULL, NULL, '1'), 
( 'due_to_bir', 'BIR', 'BIR', '1', current_timestamp(), '1', NULL, NULL, '1'), 
( 'gsis', 'GSIS', 'GSIS', '1', current_timestamp(), '1', NULL, NULL, '1');

ALTER TABLE `acctg_account_general_ledgers` 
ADD `is_gsis` INT NOT NULL DEFAULT '0' AFTER `is_payroll`, 
ADD `is_pagibig` INT NOT NULL DEFAULT '0' AFTER `is_gsis`, 
ADD `is_philhealth` INT NOT NULL DEFAULT '0' AFTER `is_pagibig`;

ALTER TABLE `hr_income_and_deduction` ADD `gl_id_debit` INT NOT NULL AFTER `sl_id`, ADD `sl_id_debit` INT NOT NULL AFTER `gl_id_debit`;
ALTER TABLE `hr_income_deduction_type` ADD `gl_id_debit` INT NOT NULL AFTER `sl_id`, ADD `sl_id_debit` INT NOT NULL AFTER `gl_id_debit`;