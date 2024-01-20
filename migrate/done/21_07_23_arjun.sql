ALTER TABLE `clients` ADD `doc_json` TEXT NULL AFTER `p_email_address`;
ALTER TABLE `hr_employees` ADD `doc_json` TEXT NULL AFTER `email_address`;