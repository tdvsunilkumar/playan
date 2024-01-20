ALTER TABLE `rpt_property_approvals` CHANGE `rp_app_cancel_by_td_no` `rp_app_cancel_by_td_id` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `rpt_property_appraisals` CHANGE `rpa_adjustment_percent` `rpa_adjustment_percent` DECIMAL(20,3) NOT NULL;
ALTER TABLE `cto_cashier_real_properties` ADD `or_no` VARCHAR(100) NULL AFTER `tfoc_is_applicable`;
ALTER TABLE `cto_cashier_real_properties` ADD `rp_property_code` BIGINT(20) NULL AFTER `rp_code`;
ALTER TABLE `cto_cashier_real_properties` ADD `tcm_id` BIGINT(20) NULL COMMENT 'hen tax credit amount>0 Ref-Table: cto_tax_credit_management.id' AFTER `transaction_no`, ADD `tax_credit_gl_id` BIGINT(20) NULL COMMENT 'when tax credit amount>0' AFTER `tcm_id`, ADD `tax_credit_sl_id` BIGINT(20) NULL COMMENT 'when tax credit amount>0' AFTER `tax_credit_gl_id`, ADD `tax_credit_amount` DECIMAL(20,3) NULL AFTER `tax_credit_sl_id`, ADD `tax_credit_is_useup` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = Used, 0 = Not Used' AFTER `tax_credit_amount`, ADD `previous_cashier_id` BIGINT(20) NULL COMMENT 'Credit amount applied cashier id' AFTER `tax_credit_is_useup`;
