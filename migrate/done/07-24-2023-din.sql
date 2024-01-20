ALTER TABLE `gso_items` ADD COLUMN IF NOT EXISTS `is_expirable` BOOLEAN NOT NULL DEFAULT FALSE AFTER `minimum_order_quantity`;
