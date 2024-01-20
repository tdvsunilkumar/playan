ALTER TABLE `ho_medical_records` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `hp_code`;
ALTER TABLE `ho_medical_records` ADD COLUMN IF NOT EXISTS `cit_age` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_medical_records` ADD COLUMN IF NOT EXISTS `cit_age_days` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_medical_records` ADD COLUMN IF NOT EXISTS `cit_gender` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_medical_records` MODIFY COLUMN IF EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `ho_medical_record_diagnoses` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `med_rec_id`;
ALTER TABLE `ho_medical_record_diagnoses` ADD COLUMN IF NOT EXISTS `cit_age` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_medical_record_diagnoses` ADD COLUMN IF NOT EXISTS `cit_age_days` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_medical_record_diagnoses` ADD COLUMN IF NOT EXISTS `cit_gender` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_medical_record_diagnoses` MODIFY COLUMN IF EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `ho_serology_details` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `ser_id`;

ALTER TABLE `ho_treatments` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `med_rec_id`;
ALTER TABLE `ho_treatments` ADD COLUMN IF NOT EXISTS `cit_age` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_treatments` ADD COLUMN IF NOT EXISTS `cit_age_days` INT NOT NULL AFTER `cit_id`;
ALTER TABLE `ho_treatments` MODIFY COLUMN IF EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;