ALTER TABLE ho_lab_fees CHANGE COLUMN ho_fee hlf_fee decimal(14,3);
ALTER TABLE ho_lab_fees CHANGE COLUMN ho_service_name hlf_service_name varchar(225);
ALTER TABLE ho_lab_fees CHANGE COLUMN lab_id lab_req_id int;
ALTER TABLE ho_lab_fees ADD COLUMN IF NOT EXISTS `lab_control_no` VARCHAR(20) NULL AFTER `lab_req_id`;

