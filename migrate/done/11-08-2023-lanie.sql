ALTER TABLE `cbo_obligation_payroll` ADD `employee_type` int NOT NULL AFTER `cutoff_id`;

ALTER TABLE acctg_debit_memos ADD COLUMN IF NOT EXISTS paid_amount DOUBLE NULL DEFAULT NULL AFTER total_amount;

ALTER TABLE acctg_debit_memos ADD COLUMN IF NOT EXISTS posted_at TIMESTAMP NULL DEFAULT NULL AFTER disapproved_remarks, ADD COLUMN IF NOT EXISTS posted_by INT NULL DEFAULT NULL AFTER posted_at;