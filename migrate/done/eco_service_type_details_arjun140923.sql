-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2023 at 10:28 AM
-- Server version: 10.4.27-MariaDB
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
-- Table structure for table `eco_service_type_details`
--

CREATE TABLE `eco_service_type_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `est_id` int(11) NOT NULL COMMENT 'ref-Table : est_service_type.est_id',
  `eat_additional_info` int(11) NOT NULL COMMENT 'ref-Table : est_service_type.est_id',
  `eatd_discount` int(11) DEFAULT 0 COMMENT 'with 20% Discount = 1, without 20% discount = 0',
  `eatd_process_type` varchar(100) DEFAULT NULL,
  `eatd_amount_type` varchar(100) DEFAULT NULL,
  `eatd_status` int(11) NOT NULL COMMENT 'active = 1, inactive = 0',
  `created_by` int(11) NOT NULL DEFAULT 0 COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NOT NULL DEFAULT 0 COMMENT 'reference hr_employee.p_code of the system  who update the details',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eco_service_type_details`
--
ALTER TABLE `eco_service_type_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eco_service_type_details`
--
ALTER TABLE `eco_service_type_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
