ALTER TABLE `dashboard_group_menus` ADD `slug` VARCHAR(255) NOT NULL AFTER `menu_name`, ADD `icon` VARCHAR(255) NULL AFTER `slug`;