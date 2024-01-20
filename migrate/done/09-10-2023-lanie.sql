ALTER TABLE `hr_tax_table` ADD COLUMN IF NOT EXISTS  `ewt_id` INT NULL DEFAULT NULL AFTER `id`;
CREATE TABLE `eco_housing_summary_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `housing_application_id` int(11) NOT NULL COMMENT 'ref-Table : legal_housing_application . id',
  `reference_id` varchar(255) NOT NULL COMMENT 'ref-Table : legal_housing_application . (reference ID)',
  `citizen_id` int(11) NOT NULL COMMENT 'ref-Table : legal_housing_application . id ( citizen_id)',
  `total_amount` double NOT NULL COMMENT 'first amount will be 15000 ( after first payment ) = remaining_balance;',
  `date_or` date NULL COMMENT 'cto_cashiers_details . id ( created_at )',
  `or_no` varchar(255) NULL COMMENT 'cto_cashiers_details . id ( or_no )',
  `paid_amount` double NULL COMMENT 'cto_cashiers_details . id ( tfc_amount )',
  `balance` double NULL COMMENT '( total amount - paid_amount )',
  `payment_status` int NULL COMMENT '0 = unpaid 1 = partial paid, 2 = fully paid;',
  `created_by` int(11) NOT NULL COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss].' ,
  `created_at` timestamp NOT NULL COMMENT 'reference hr_employee.p_code of the system who update the details',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00' ,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;