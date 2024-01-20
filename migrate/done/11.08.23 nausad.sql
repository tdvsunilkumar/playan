-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 10, 2023 at 08:27 PM
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
-- Table structure for table `cpdo_development_permit_computation_lines`
--

CREATE TABLE `cpdo_development_permit_computation_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `cdpc_id` int NOT NULL,
  `cdpcl_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cdpcl_amount` decimal(8,2) DEFAULT NULL,
  `cis_id` int DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpdo_development_permit_computation_lines`
--

INSERT INTO `cpdo_development_permit_computation_lines` (`id`, `cdpc_id`, `cdpcl_description`, `cdpcl_amount`, `cis_id`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 1, 'Lorem Ipsum Dolor Sit Amet 1', 200.00, 1, 1, 1, '2023-08-11 06:09:12', '2023-08-11 06:09:12', 1),
(2, 1, 'Lorem Ipsum Dolor Sit Amet 2', 300.00, 2, 1, 1, '2023-08-11 06:09:12', '2023-08-11 06:09:12', 1),
(3, 1, 'Lorem Ipsum Dolor Sit Amet 3', 400.00, 1, 1, 1, '2023-08-11 06:09:12', '2023-08-11 08:26:41', 1),
(4, 2, 'Lorem Ipsum Dolor Sit Amet 10', 960.00, 1, 1, 1, '2023-08-11 07:20:53', '2023-08-11 07:20:53', 1),
(5, 2, 'Lorem Ipsum Dolor Sit Amet 11', 1050.00, 3, 1, 1, '2023-08-11 07:20:53', '2023-08-11 07:20:53', 1),
(6, 1, 'Lorem Ipsum Dolor Sit Amet 44', 900.00, 1, 1, 1, '2023-08-11 08:25:13', '2023-08-11 08:25:38', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cpdo_development_permit_computation_lines`
--
ALTER TABLE `cpdo_development_permit_computation_lines`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cpdo_development_permit_computation_lines`
--
ALTER TABLE `cpdo_development_permit_computation_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
