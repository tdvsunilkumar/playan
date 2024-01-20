-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2023 at 05:27 AM
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
-- Table structure for table `eco_receptions_lists`
--

CREATE TABLE `eco_receptions_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barangay_id` int(11) NOT NULL COMMENT 'ref-Table barangays.id',
  `est_service_type` varchar(100) NOT NULL COMMENT 'Cemetery modules = 1, Event Module = 2',
  `est_addtional_info` int(11) DEFAULT 0 COMMENT 'Checked = 1, unchecked = 0',
  `est_year_month` int(11) DEFAULT NULL COMMENT 'checked Yearly = 1, Checked Monthly = 2;',
  `est_status` int(11) NOT NULL COMMENT 'active = 1, inactive = 0',
  `created_by` int(11) NOT NULL DEFAULT 0 COMMENT 'reference hr_employee.p_code of the system who registered the details',
  `updated_by` int(11) NOT NULL DEFAULT 0 COMMENT 'reference hr_employee.p_code of the system  who update the details',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eco_receptions_lists`
--
ALTER TABLE `eco_receptions_lists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eco_receptions_lists`
--
ALTER TABLE `eco_receptions_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
