ALTER TABLE `rpt_property_tax_certs` ADD `cashier_id` BIGINT NULL COMMENT 'Reference Table cto_cashier.id' AFTER `id`, ADD `cashier_detail_id` BIGINT NULL COMMENT 'Reference Table cto_cashier_details.id' AFTER `cashier_id`;
ALTER TABLE `rpt_property_tax_certs` CHANGE `rptc_control_no` `rptc_control_no` VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE `rpt_property_tax_certs` CHANGE `rptc_owner_tin_no` `rptc_owner_tin_no` VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE `rpt_property_appraisals` CHANGE `rpa_total_land_area` `rpa_total_land_area` DECIMAL(10,5) NOT NULL;