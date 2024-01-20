ALTER TABLE `rpt_property_certs` ADD `cashierd_id` INT(11) NULL DEFAULT NULL COMMENT 'Ref-Table: cto_cashier_details.id' AFTER `rpc_date`, ADD `cashier_id` INT(11) NULL DEFAULT NULL COMMENT 'Ref-Table:cto_cashier.id' AFTER `cashierd_id`;
ALTER TABLE `rpt_property_certs` ADD `status` INT(1) NOT NULL DEFAULT '1' COMMENT 'Status... 0=InActive, 1=Active' AFTER `rpc_remarks`;
ALTER TABLE `rpt_property_certs` CHANGE `rpc_or_date` `rpc_or_date` DATE NULL DEFAULT NULL;

ALTER TABLE `rpt_property_certs` CHANGE `rpc_year` `rpc_year` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Current Year';