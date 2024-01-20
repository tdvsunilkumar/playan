ALTER TABLE `ho_water_potabilities` CHANGE `or_no` `or_no` VARCHAR(100) NULL COMMENT 'cto_cashier.or_no', 
CHANGE `or_date` `or_date` DATE NULL COMMENT 'cto_cashier.cashier_or_date', 
CHANGE `or_amount` `or_amount` DOUBLE NULL COMMENT 'cto_cashier_details.tfc_amount';