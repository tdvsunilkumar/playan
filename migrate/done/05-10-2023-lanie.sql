ALTER TABLE `hr_leave_earning_adjustment_detail` CHANGE `hrlpc_days` `hrlpc_days` INT(11) NULL DEFAULT '0' COMMENT 'get number from Leave Parameter # Of Days', CHANGE `hrlead_used` `hrlead_used` INT(11) NULL DEFAULT '0' COMMENT 'Used', CHANGE `hrlead_balance` `hrlead_balance` INT(11) NULL DEFAULT '0' COMMENT 'Balance';