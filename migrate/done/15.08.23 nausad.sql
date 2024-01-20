-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 15, 2023 at 04:49 PM
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
-- Table structure for table `ho_inventory_breakdowns`
--

CREATE TABLE `ho_inventory_breakdowns` (
  `id` bigint UNSIGNED NOT NULL,
  `inv_posting_id` int NOT NULL,
  `item_id` int NOT NULL,
  `hrb_date_received` date NOT NULL,
  `hrb_unit_cost` decimal(8,2) NOT NULL,
  `hrb_total_cost` decimal(8,2) NOT NULL,
  `hrb_qty_posted` int NOT NULL,
  `hrb_issued_qty` int DEFAULT NULL,
  `hrb_balance_qty` int DEFAULT NULL,
  `hrb_adjust_qty` int DEFAULT NULL,
  `hrb_uom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hrb_expiry_date` date DEFAULT NULL,
  `hrb_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hrb_remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_inventory_breakdowns`
--

INSERT INTO `ho_inventory_breakdowns` (`id`, `inv_posting_id`, `item_id`, `hrb_date_received`, `hrb_unit_cost`, `hrb_total_cost`, `hrb_qty_posted`, `hrb_issued_qty`, `hrb_balance_qty`, `hrb_adjust_qty`, `hrb_uom`, `hrb_expiry_date`, `hrb_status`, `hrb_remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, 5, 27, '2023-08-15', 10.25, 20.50, 2, 0, 2, 0, '23', '2023-08-24', '0', 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-08-16 04:45:00', '2023-08-16 04:45:00'),
(6, 5, 27, '2023-08-15', 10.25, 30.75, 3, 0, 3, 0, '23', '2023-08-31', '0', 'Lorem Ipsum Dolor Sit Amet', 1, 1, '2023-08-16 04:45:00', '2023-08-16 04:45:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_inventory_breakdowns`
--
ALTER TABLE `ho_inventory_breakdowns`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_inventory_breakdowns`
--
ALTER TABLE `ho_inventory_breakdowns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
