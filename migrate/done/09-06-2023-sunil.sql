ALTER TABLE `rpt_cto_billing_details` ADD `basic_total_due` DECIMAL(20,2) NULL AFTER `sh_sl_id`;
ALTER TABLE `rpt_cto_billing_details_discounts` ADD `dicount_total_due` DECIMAL(20,2) NULL AFTER `sh_discount_amount`;
ALTER TABLE `rpt_cto_billing_details_penalties` ADD `penalty_total_due` DECIMAL(20,2) NULL AFTER `sh_penalty_amount`;
ALTER TABLE `rpt_cto_billings` CHANGE `rp_suffix` `rp_suffix` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;