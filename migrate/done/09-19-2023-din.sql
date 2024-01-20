ALTER TABLE `eco_rental_application` ADD COLUMN IF NOT EXISTS `event_title` VARCHAR(255) NOT NULL AFTER `full_address`;

ALTER TABLE `eco_cemetery_application` ADD COLUMN IF NOT EXISTS `remaining_amount` DOUBLE NULL DEFAULT NULL AFTER `total_amount`;


