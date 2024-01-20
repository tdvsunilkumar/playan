CREATE TABLE `ho_medical_record_diagnoses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
 `med_rec_id` int(11) NOT NULL COMMENT 'table:ho_medical_record. med_rec_id',
  `disease_id` int(11) NOT NULL COMMENT 'ref-table:ho_diseases.id',
  `is_specified` varchar(225) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL
  PRIMARY KEY(id)
) ;

CREATE TABLE `ho_treatments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treat_id` int(11) NOT NULL,
  `med_rec_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-table:ho_medical_record. med_rec_id',
  `treat_medication` varchar(225) DEFAULT NULL,
  `treat_management` varchar(225) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `treat_is_active` int(11) DEFAULT NULL
  PRIMARY KEY(id)
);