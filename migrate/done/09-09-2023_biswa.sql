SET FOREIGN_KEY_CHECKS=0;
SET innodb_strict_mode=0;ALTER TABLE `clients`  ADD `is_synced` BOOLEAN NULL DEFAULT FALSE  AFTER `last_login_at`;
UPDATE `clients` SET `is_synced` = 1;