ALTER TABLE `cto_petty_cash` CHANGE `disbursement_approved_at` `approved_at` TIMESTAMP NULL DEFAULT NULL, CHANGE `disbursement_approved_by` `approved_by` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `disbursement_disapproved_at` `disapproved_at` TIMESTAMP NULL DEFAULT NULL, CHANGE `disbursement_disapproved_by` `disapproved_by` INT(10) UNSIGNED NULL DEFAULT NULL, CHANGE `disbursement_disapproved_remarks` `disapproved_remarks` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `cto_petty_cash`
  DROP `replenishment_approved_at`,
  DROP `replenishment_approved_by`,
  DROP `replenishment_disapproved_at`,
  DROP `replenishment_disapproved_by`,
  DROP `replenishment_disapproved_remarks`;

  ALTER TABLE `bac_rfqs` ADD COLUMN IF NOT EXISTS `purchase_type_id` INT NULL DEFAULT NULL COMMENT 'gso_purchase_types' AFTER `fund_code_id`;

  RENAME TABLE `cto_petty_cash` TO `cto_disburse`;

  RENAME TABLE `cto_petty_cash_details` TO `cto_disburse_details`;

  ALTER TABLE `cto_disburse_details` CHANGE `petty_cash_id` `disburse_id` INT(10) UNSIGNED NOT NULL COMMENT 'cto_disburse';

  CREATE TABLE IF NOT EXISTS `cto_replenish` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `control_no` varchar(40) NOT NULL,
  `particulars` text DEFAULT NULL,
  `total_amount` double NOT NULL DEFAULT 0,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `sent_at` timestamp NULL DEFAULT NULL,
  `sent_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` text DEFAULT NULL,
  `disapproved_at` timestamp NULL DEFAULT NULL,
  `disapproved_by` int(10) UNSIGNED DEFAULT NULL,
  `disapproved_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cto_replenish`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `cto_replenish`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `cto_replenish_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `replenish_id` int(10) UNSIGNED NOT NULL COMMENT 'cto_replenish',
  `disburse_id` int(10) UNSIGNED NOT NULL COMMENT 'cto_disburse',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cto_replenish_details`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `cto_replenish_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

  UPDATE `gso_unit_of_measurements` SET `is_lot` = '1' WHERE `gso_unit_of_measurements`.`id` = 28;

  ALTER TABLE `acctg_payables` CHANGE `vat_type` `vat_type` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Vatable';