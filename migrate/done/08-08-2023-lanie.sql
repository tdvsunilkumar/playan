CREATE TABLE `biometrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bio_desc` VARCHAR(225) NULL,
  `bio_model` VARCHAR(225) NULL,
  `bio_code` VARCHAR(225) NOT NULL,
  `bio_department` VARCHAR(225) NULL,
  `bio_ip` VARCHAR(225) NOT NULL,
  `bio_proxy` int NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;
