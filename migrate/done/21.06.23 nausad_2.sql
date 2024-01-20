-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 21, 2023 at 11:21 PM
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
-- Table structure for table `ho_inventory_adjustments`
--

CREATE TABLE `ho_inventory_adjustments` (
  `id` bigint UNSIGNED NOT NULL,
  `hia_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hia_no` int NOT NULL,
  `hia_series` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hia_remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hia_status` int NOT NULL,
  `is_active` tinyint NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_inventory_adjustments`
--

INSERT INTO `ho_inventory_adjustments` (`id`, `hia_year`, `hia_no`, `hia_series`, `hia_remarks`, `hia_status`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '2023', 1, '0001', 'Lorem Ipsum Dolor Sit Amet', 1, 1, 1, 1, '2023-06-22 08:36:58', '2023-06-22 08:36:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_inventory_adjustments`
--
ALTER TABLE `ho_inventory_adjustments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_inventory_adjustments`
--
ALTER TABLE `ho_inventory_adjustments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
