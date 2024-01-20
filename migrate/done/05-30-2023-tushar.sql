ALTER TABLE `cto_cashier` CHANGE `tax_credit_tfoc_id` `tcm_id` INT(11) NOT NULL COMMENT 'when tax credit amount>0 Ref-Table: cto_tax_credit_management.id';
ALTER TABLE `cto_cashier` ADD `cashier_or_date` DATE NULL DEFAULT NULL AFTER `cashier_month`;
ALTER TABLE `cto_cashier` ADD `is_included_cashier_amt` INT(1) NOT NULL DEFAULT '0' AFTER `previous_cashier_id`;
