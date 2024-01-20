ALTER TABLE `ho_inventory_category` ADD `cat_is_active` tinyint AFTER `updated_by`;
ALTER TABLE `ho_inventory_posting` ADD `civ_is_active` tinyint AFTER `updated_by`;
ALTER TABLE `ho_inventory_category` MODIFY `inv_category` VARCHAR(255);