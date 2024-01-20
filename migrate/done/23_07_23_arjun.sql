ALTER TABLE `ho_lab_requests` ADD `doc_json` TEXT NULL AFTER `lab_req_diagnosis`;
ALTER TABLE `ho_record_cards` ADD `doc_json` TEXT NULL AFTER `rec_card_occupation`;
ALTER TABLE `ho_medical_records` ADD `doc_json` TEXT NULL AFTER `med_rec_nurse_note`;
ALTER TABLE `ho_medical_certificates` ADD `doc_json` TEXT NULL AFTER `med_officer_approved_status`;
ALTER TABLE `ho_fam_plan` ADD `doc_json` TEXT NULL AFTER `age`;
