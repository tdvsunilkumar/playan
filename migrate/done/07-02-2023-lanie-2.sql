ALTER TABLE ho_treatments MODIFY COLUMN IF EXISTS `treat_management` varchar(225) NULL;
ALTER TABLE ho_treatments MODIFY COLUMN IF EXISTS `treat_medication` varchar(225) NULL;
ALTER TABLE ho_medical_records MODIFY COLUMN IF EXISTS `treat_medication` varchar(225) NULL;
ALTER TABLE ho_medical_certificates MODIFY COLUMN IF EXISTS `or_no` varchar(225) NULL;
ALTER TABLE ho_medical_certificates MODIFY COLUMN IF EXISTS `or_date` date NULL;
ALTER TABLE ho_medical_certificates MODIFY COLUMN IF EXISTS `or_amount` decimal(14,2) NULL;
ALTER TABLE ho_medical_certificates MODIFY COLUMN IF EXISTS `cashier_id` int NULL;
ALTER TABLE ho_medical_certificates MODIFY COLUMN IF EXISTS `cashierd_id` int NULL;
