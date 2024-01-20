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
-- Table structure for table `cpdo_zoning_computation_clearance`
--

CREATE TABLE `cpdo_zoning_computation_clearance` (
  `id` bigint UNSIGNED NOT NULL,
  `cm_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpdo_zoning_computation_clearance`
--

INSERT INTO `cpdo_zoning_computation_clearance` (`id`, `cm_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '10', 1, 1, '2023-08-12 03:49:31', '2023-08-12 04:32:26'),
(2, '11', 1, 1, '2023-08-12 03:52:39', '2023-08-12 03:52:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cpdo_zoning_computation_clearance`
--
ALTER TABLE `cpdo_zoning_computation_clearance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cpdo_zoning_computation_clearance`
--
ALTER TABLE `cpdo_zoning_computation_clearance`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
