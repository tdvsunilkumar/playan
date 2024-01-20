-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 11, 2023 at 08:13 PM
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
-- Table structure for table `ho_inventory_utilizations`
--

CREATE TABLE `ho_inventory_utilizations` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `util_rep_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `util_rep_range` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `util_rep_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `util_rep_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `util_rep_size` decimal(10,2) DEFAULT NULL,
  `util_rep_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `util_rep_remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `util_rep_status` tinyint NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_inventory_utilizations`
--

INSERT INTO `ho_inventory_utilizations` (`id`, `supplier_id`, `util_rep_name`, `util_rep_range`, `util_rep_year`, `util_rep_type`, `util_rep_size`, `util_rep_path`, `util_rep_remarks`, `util_rep_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, '[7,9]', '2022', '2', NULL, NULL, 'test', 1, 1, 1, '2023-07-12 07:14:02', '2023-07-12 07:14:02'),
(2, NULL, NULL, '3', '2023', '1', NULL, NULL, 'Lorem Ipsum Dolor Sit Amet', 1, 1, 1, '2023-07-12 07:58:31', '2023-07-12 07:58:31'),
(3, NULL, NULL, '[10,12]', '2023', '1', NULL, NULL, 'Lorem Ipsum Dolor Sit Amet...', 1, 1, 1, '2023-07-12 07:59:37', '2023-07-12 07:59:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_inventory_utilizations`
--
ALTER TABLE `ho_inventory_utilizations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_inventory_utilizations`
--
ALTER TABLE `ho_inventory_utilizations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
