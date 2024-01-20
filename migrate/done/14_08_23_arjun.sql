ALTER TABLE `ho_request_permit` CHANGE `top_transaction_no` `top_transaction_no` INT(11) NULL DEFAULT '0' COMMENT 'ref-table:cto_top_transactions. id...... use top_transaction_no';
ALTER TABLE `ho_request_permit` CHANGE `trans_id` `trans_id` INT(11) NULL DEFAULT '0' COMMENT 'ref-table:cto_top_transactions. id';
