ALTER TABLE `cto_receivables` CHANGE `amount_pay` `amount_pay` DOUBLE(14,2) NULL COMMENT 'ref-Table : cto_cashier_details.total_amount';
ALTER TABLE `cto_receivables` CHANGE `or_no` `or_no` varchar(255) NULL COMMENT 'Or No';