ALTER TABLE ho_medical_records ADD COLUMN IF NOT EXISTS `med_rec_date` date NULL AFTER `med_rec_id`;
ALTER TABLE ho_medical_records ADD COLUMN IF NOT EXISTS `med_rec_status` int NULL AFTER `med_rec_nurse_note`;
ALTER TABLE ho_medical_records MODIFY COLUMN IF EXISTS `med_rec_nurse_note` varchar(225) NULL;
ALTER TABLE ho_medical_record_diagnoses ADD COLUMN IF NOT EXISTS `is_active` int NULL;
ALTER TABLE ho_medical_record_diagnoses MODIFY COLUMN IF EXISTS `is_specified` varchar(225) NULL;
ALTER TABLE ho_treatments ADD COLUMN IF NOT EXISTS `treat_is_active` int NULL;
