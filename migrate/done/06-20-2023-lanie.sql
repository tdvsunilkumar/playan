CREATE TABLE ho_lab_fees (
    id int PRIMARY KEY AUTO_INCREMENT,
    lab_id int NOT NULL,
    service_id int NULL,
    cit_id int NOT NULL,
    ho_fee decimal(14,3) NULL,
    ho_service_name VARCHAR(225) NULL,
    created_by int, 
    created_at DATETIME, 
    updated_by int, 
    updated_at DATETIME
)

ALTER TABLE `ho_lab_requests` ADD COLUMN IF NOT EXISTS `lab_is_free` INT DEFAULT(0) AFTER `lab_req_or`;
ALTER TABLE `ho_lab_requests` ADD COLUMN IF NOT EXISTS `lab_reg_date` INT NULL AFTER `lab_req_year`;
ALTER TABLE `ho_lab_requests` MODIFY COLUMN IF EXISTS `trans_id` INT NULL;
ALTER TABLE `ho_lab_requests` MODIFY COLUMN IF EXISTS `lab_req_or` INT NULL;
