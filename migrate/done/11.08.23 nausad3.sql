-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 11, 2023 at 04:40 PM
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
-- Table structure for table `cpdo_zoning_computation_clearance_lines`
--

CREATE TABLE `cpdo_zoning_computation_clearance_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `czcc_id` int NOT NULL,
  `czccl_below` decimal(8,2) DEFAULT NULL,
  `czccl_over` decimal(8,2) DEFAULT NULL,
  `czccl_over_by_amount` int DEFAULT NULL,
  `czccl_amount` decimal(8,2) DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpdo_zoning_computation_clearance_lines`
--

INSERT INTO `cpdo_zoning_computation_clearance_lines` (`id`, `czcc_id`, `czccl_below`, `czccl_over`, `czccl_over_by_amount`, `czccl_amount`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 100.00, NULL, 1, 200.00, 1, 1, 1, '2023-08-12 03:49:31', '2023-08-12 04:30:33'),
(2, 1, 150.00, NULL, 1, 250.00, 1, 1, 1, '2023-08-12 03:49:31', '2023-08-12 04:30:33'),
(3, 1, 350.00, NULL, 1, 263.00, 1, 1, 1, '2023-08-12 03:49:31', '2023-08-12 04:32:26'),
(4, 2, 450.00, 650.00, 0, 1200.00, 1, 1, 1, '2023-08-12 03:52:39', '2023-08-12 03:52:39'),
(5, 2, 650.00, NULL, 1, 1300.00, 1, 1, 1, '2023-08-12 03:52:39', '2023-08-12 03:52:39'),
(6, 1, 520.00, NULL, 1, 630.00, 1, 1, 1, '2023-08-12 04:30:33', '2023-08-12 04:30:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cpdo_zoning_computation_clearance_lines`
--
ALTER TABLE `cpdo_zoning_computation_clearance_lines`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cpdo_zoning_computation_clearance_lines`
--
ALTER TABLE `cpdo_zoning_computation_clearance_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
