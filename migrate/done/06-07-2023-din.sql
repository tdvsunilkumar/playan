DROP TABLE `gso_issuance`, `gso_issuance_details`;

UPDATE `acctg_account_general_ledgers` SET `is_payable` = '1' WHERE `acctg_account_general_ledgers`.`id` = 288;
ALTER TABLE `acctg_vouchers` CHANGE `payee_id` `payee_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'cbo_payee';

ALTER TABLE `acctg_account_general_ledgers` ADD `is_due_to_bir` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_payable`;
UPDATE `acctg_account_general_ledgers` SET `is_due_to_bir` = '1' WHERE `acctg_account_general_ledgers`.`id` = 304;

INSERT INTO `acctg_payment_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'CS', 'Cash', 'CS - Cash Payment', '2023-05-29 03:39:40', 1, NULL, NULL, 1),
(2, 'CH', 'Cheque', 'CH - Cheque Payment', '2023-05-29 03:39:54', 1, NULL, NULL, 1),
(3, 'CC', 'Credit Card', 'CC - Credit Card Payment', '2023-05-29 03:40:53', 1, NULL, NULL, 1),
(4, 'MT', 'Money Transfer', 'MT - Money Transfer Payment', '2023-05-29 03:41:07', 1, NULL, NULL, 1);
