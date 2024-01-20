CREATE TABLE IF NOT EXISTS `ho_issuances` (
  `id` bigint(20) unsigned NOT NULL,
  `receiver_name` int(11) NOT NULL,
  `receiver_age` varchar(255) DEFAULT NULL,
  `receiver_brgy` varchar(255) DEFAULT NULL,
  `ho_inv_posting_id` int(11) NOT NULL,
  `hp_code` int(11) NOT NULL,
  `issuance_item_name` varchar(255) NOT NULL,
  `issuance_code` varchar(255) NOT NULL,
  `issuance_quantity` int(11) NOT NULL,
  `issuance_uom` varchar(255) NOT NULL,
  `issuance_type` int(11) NOT NULL,
  `issuance_status` int(11) NOT NULL,
  `issuance_series` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

