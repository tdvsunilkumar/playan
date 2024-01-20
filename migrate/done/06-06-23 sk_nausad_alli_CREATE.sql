-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2023 at 10:11 PM
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
-- Table structure for table `ho_inventory_posting`
--

CREATE TABLE `ho_inventory_posting` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` int NOT NULL,
  `inv_cat_id` int NOT NULL,
  `sup_id` int NOT NULL,
  `cip_receiving` int NOT NULL,
  `cip_control_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cip_date_received` datetime DEFAULT NULL,
  `cip_item_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cip_item_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cip_unit_cost` decimal(14,2) NOT NULL,
  `cip_total_cost` decimal(14,2) NOT NULL,
  `cip_qty_posted` int NOT NULL,
  `cip_issued_qty` int NOT NULL,
  `cip_balance_qty` int NOT NULL,
  `cip_adjust_qty` int NOT NULL,
  `cip_uom` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cip_expiry_date` datetime DEFAULT NULL,
  `cip_status` tinyint NOT NULL,
  `cip_remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_inventory_posting`
--

INSERT INTO `ho_inventory_posting` (`id`, `item_id`, `inv_cat_id`, `sup_id`, `cip_receiving`, `cip_control_no`, `cip_date_received`, `cip_item_code`, `cip_item_name`, `cip_unit_cost`, `cip_total_cost`, `cip_qty_posted`, `cip_issued_qty`, `cip_balance_qty`, `cip_adjust_qty`, `cip_uom`, `cip_expiry_date`, `cip_status`, `cip_remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 53, 1, 2, 1, '00001', '2023-06-07 00:00:00', 'AFS-00038', 'bread toaster', 200.00, 200.00, 1, 0, 1, 0, '1', '2023-06-07 00:00:00', 2, 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-06-07 09:46:09', '2023-06-07 09:46:09'),
(2, 54, 1, 2, 1, '00001', '2023-06-07 00:00:00', 'AFS-00039', 'bottle sterilizer, electric', 10.25, 0.00, 0, 0, 0, 0, '1', '2023-06-07 00:00:00', 2, 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-06-07 09:46:09', '2023-06-07 09:46:09'),
(3, 53, 1, 18, 2, '00001', '2023-06-27 00:00:00', 'AFS-00038', 'bread toaster', 200.00, 200.00, 1, 0, 1, 0, '1', '2023-06-07 00:00:00', 2, 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-06-07 09:57:03', '2023-06-07 09:57:03'),
(4, 54, 1, 18, 2, '00001', '2023-06-27 00:00:00', 'AFS-00039', 'bottle sterilizer, electric', 10.25, 0.00, 0, 0, 0, 0, '1', '2023-06-07 00:00:00', 2, 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-06-07 09:57:03', '2023-06-07 09:57:03'),
(5, 53, 1, 16, 1, '00001', '2023-06-14 00:00:00', 'AFS-00038', 'bread toaster', 200.00, 200.00, 1, 0, 1, 0, '1', '2023-06-07 00:00:00', 2, 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-06-07 10:02:35', '2023-06-07 10:02:35'),
(6, 54, 1, 16, 1, '00001', '2023-06-14 00:00:00', 'AFS-00039', 'bottle sterilizer, electric', 10.25, 0.00, 0, 0, 0, 0, '1', '2023-06-07 00:00:00', 2, 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-06-07 10:02:35', '2023-06-07 10:02:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_inventory_posting`
--
ALTER TABLE `ho_inventory_posting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_inventory_posting`
--
ALTER TABLE `ho_inventory_posting`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
