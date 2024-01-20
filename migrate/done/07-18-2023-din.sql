INSERT INTO `cbo_obligation_types` (`id`, `fund_code_id`, `gl_account_id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES (NULL, '1', NULL, 'REPL', 'Replenishment', 'REPL - Replenishment', '2023-07-05 19:08:05', '1', NULL, NULL, '1');

ALTER TABLE `cto_disburse` ADD COLUMN IF NOT EXISTS `is_disbursed` BOOLEAN NOT NULL DEFAULT FALSE AFTER `disapproved_remarks`;
ALTER TABLE `cto_disburse` ADD COLUMN IF NOT EXISTS `is_replenished` BOOLEAN NOT NULL DEFAULT FALSE AFTER `disapproved_remarks`;

ALTER TABLE `cbo_allotment_obligations_requests` CHANGE `approved_by` `approved_by` TEXT NULL DEFAULT NULL, CHANGE `disapproved_by` `disapproved_by` TEXT NULL DEFAULT NULL;

ALTER TABLE `cbo_obligation_types` CHANGE `gl_account_id` `gl_account_id` TEXT NULL DEFAULT NULL COMMENT 'acctg_account_general_ledgers';

INSERT INTO `cbo_obligation_types` (`id`, `fund_code_id`, `gl_account_id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES (NULL, '1', NULL, 'REPR', 'Repairs and Maintenance', 'REPR - Repairs and Maintenance', '2023-07-05 19:08:05', '1', NULL, NULL, '1');

ALTER TABLE `gso_departmental_requests` ADD COLUMN IF NOT EXISTS `obligation_type_id` INT NOT NULL DEFAULT '1' COMMENT 'cbo_obligation_types' AFTER `id`;