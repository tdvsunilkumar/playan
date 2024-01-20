ALTER TABLE `cto_receivables` CHANGE `assessed_value` `assessed_value` DOUBLE(14,2) NULL DEFAULT NULL, CHANGE `amount_basic` `amount_basic` DOUBLE(14,2) NULL DEFAULT NULL COMMENT 'Basic Due', CHANGE `amount_set` `amount_set` DOUBLE(14,2) NULL DEFAULT NULL COMMENT 'Amount Set', CHANGE `amount_socialize` `amount_socialize` DOUBLE(14,2) NULL DEFAULT NULL COMMENT 'Amount Due', CHANGE `amount_pay` `amount_pay` DOUBLE(14,2) NULL DEFAULT NULL COMMENT 'ref-Table : cto_cashier_details.total_amount', CHANGE `or_no` `or_no` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Or No';

ALTER TABLE `cto_receivables` CHANGE `is_paid` `is_paid` INT(11) NOT NULL DEFAULT '0';