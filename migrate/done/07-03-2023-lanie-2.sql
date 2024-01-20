Drop Table ho_medical_records;
CREATE TABLE `ho_medical_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `med_rec_id` int(11) NOT NULL,
  `med_rec_date` datetime DEFAULT NULL,
  `rec_card_id` int(11) NOT NULL COMMENT 'ref-table:ho_record_card. rec_card_id',
  `hp_code` int(11) NOT NULL COMMENT 'ref-table:hr_profile. hp_code. Get Doctor/Nurse Name',
  `med_rec_nurse_note` varchar(225) DEFAULT NULL,
  `med_rec_status` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY(id)
);