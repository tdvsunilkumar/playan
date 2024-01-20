ALTER TABLE `ho_medical_records` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `hp_code`;
ALTER TABLE `ho_medical_record_diagnoses` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `med_rec_id`;
ALTER TABLE `ho_serology_details` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `ser_id`;
ALTER TABLE `ho_treatments` ADD COLUMN IF NOT EXISTS `cit_id` INT NOT NULL AFTER `med_rec_id`;