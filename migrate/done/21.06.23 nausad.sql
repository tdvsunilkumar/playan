-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 21, 2023 at 11:18 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `palayan`
--

-- --------------------------------------------------------

--
-- Table structure for table `ho_inventory_adjustment_details`
--

CREATE TABLE `ho_inventory_adjustment_details` (
  `id` bigint UNSIGNED NOT NULL,
  `hia_id` int NOT NULL,
  `ho_inv_posting_id` int NOT NULL,
  `inv_cat_id` int NOT NULL,
  `item_id` int NOT NULL,
  `hiad_series` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hiad_qty` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hiad_uom` int NOT NULL,
  `hiad_status` int NOT NULL,
  `hiad_remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_inventory_adjustment_details`
--

INSERT INTO `ho_inventory_adjustment_details` (`id`, `hia_id`, `ho_inv_posting_id`, `inv_cat_id`, `item_id`, `hiad_series`, `hiad_qty`, `hiad_uom`, `hiad_status`, `hiad_remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 53, '0001', '-2', 1, 1, 'testing', 1, 1, '2023-06-22 08:36:58', '2023-06-22 08:36:58'),
(2, 1, 3, 1, 16, '0001', '-2', 27, 1, 'testing', 1, 1, '2023-06-22 08:36:58', '2023-06-22 08:36:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_inventory_adjustment_details`
--
ALTER TABLE `ho_inventory_adjustment_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_inventory_adjustment_details`
--
ALTER TABLE `ho_inventory_adjustment_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
