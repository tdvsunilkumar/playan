ALTER TABLE `sms_outbox` CHANGE `status` `status` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'delivered';

ALTER TABLE `gso_property_accountabilities` ADD COLUMN IF NOT EXISTS `total_depreciation` DOUBLE NOT NULL DEFAULT '0' AFTER `monthly_depreciation`;