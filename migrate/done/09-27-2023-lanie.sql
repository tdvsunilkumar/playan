ALTER TABLE `ho_medical_certificates` ADD `med_cert_findings` TEXT  NULL AFTER `incedent_datetime`;
ALTER TABLE `ho_medical_certificates` ADD `med_cert_cit_age` VARCHAR(20)  NULL AFTER `incedent_datetime`;