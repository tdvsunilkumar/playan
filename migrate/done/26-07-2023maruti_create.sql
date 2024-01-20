-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2023 at 07:45 AM
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
-- Table structure for table `hr_loan_types`
--

CREATE TABLE `hr_loan_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrlt_description` varchar(255) NOT NULL COMMENT 'Description',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_loan_types`
--

INSERT INTO `hr_loan_types` (`id`, `hrlt_description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Advance', 1, 1, 1, '2023-07-26 04:49:31', '2023-07-26 06:18:42');

-- --------------------------------------------------------

--
-- Table structure for table `hr_pay_codes`
--

CREATE TABLE `hr_pay_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrpc_description` varchar(255) NOT NULL COMMENT 'Description',
  `hrpc_code` varchar(255) NOT NULL COMMENT 'Code',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_pay_codes`
--

INSERT INTO `hr_pay_codes` (`id`, `hrpc_description`, `hrpc_code`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Regular', 'Reg', 1, 1, 1, '2023-07-26 04:13:13', '2023-07-26 04:13:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_loan_types`
--
ALTER TABLE `hr_loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_pay_codes`
--
ALTER TABLE `hr_pay_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_loan_types`
--
ALTER TABLE `hr_loan_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_pay_codes`
--
ALTER TABLE `hr_pay_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
