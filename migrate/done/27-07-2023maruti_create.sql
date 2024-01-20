-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2023 at 07:28 AM
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
-- Table structure for table `hr_cutoff_period`
--

CREATE TABLE `hr_cutoff_period` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrcp_description` varchar(255) NOT NULL COMMENT 'hrcp_description',
  `hrcp_date_from` date NOT NULL,
  `hrcp_date_to` date NOT NULL,
  `hrcp_status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_phil_healths`
--

CREATE TABLE `hr_phil_healths` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrpt_description` varchar(255) NOT NULL COMMENT 'Description',
  `hrpt_amount_from` double(8,2) NOT NULL COMMENT 'From',
  `hrpt_amount_to` double(8,2) NOT NULL COMMENT 'To',
  `hrpt_percentage` int(11) NOT NULL COMMENT 'Percentage',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_phil_healths`
--

INSERT INTO `hr_phil_healths` (`id`, `hrpt_description`, `hrpt_amount_from`, `hrpt_amount_to`, `hrpt_percentage`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Description', 2000.00, 5000.00, 6, 1, 1, 1, '2023-07-26 10:37:03', '2023-07-26 10:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `hr_tax_table`
--

CREATE TABLE `hr_tax_table` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrtt_description` varchar(255) NOT NULL COMMENT 'hrtt_description',
  `hrtt_amount_from` double(8,2) NOT NULL,
  `hrtt_amount_to` double(8,2) NOT NULL,
  `hrtt_percentage` int(11) NOT NULL,
  `hrtt_status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_tax_table`
--

INSERT INTO `hr_tax_table` (`id`, `hrtt_description`, `hrtt_amount_from`, `hrtt_amount_to`, `hrtt_percentage`, `hrtt_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'sample tax', 2000.00, 50000.00, 3, 1, 1, 1, '2023-07-27 05:35:50', '2023-07-27 05:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `hr_timecards`
--

CREATE TABLE `hr_timecards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrtc_employeesid` int(11) NOT NULL COMMENT 'Employee Id',
  `hrtc_employeesidno` int(11) NOT NULL COMMENT 'Employee Indentification No',
  `hrtc_department_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department_id',
  `hrtc_division_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department__division_id',
  `hrtc_date` date NOT NULL COMMENT 'Date',
  `hrtc_time_in` time NOT NULL COMMENT 'In time',
  `hrtc_time_out` time NOT NULL COMMENT 'Out Time',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_timecards`
--

INSERT INTO `hr_timecards` (`id`, `hrtc_employeesid`, `hrtc_employeesidno`, `hrtc_department_id`, `hrtc_division_id`, `hrtc_date`, `hrtc_time_in`, `hrtc_time_out`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 3, 2, '2023-07-27', '08:28:56', '14:19:56', 0, 1, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_cutoff_period`
--
ALTER TABLE `hr_cutoff_period`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_phil_healths`
--
ALTER TABLE `hr_phil_healths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_tax_table`
--
ALTER TABLE `hr_tax_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_timecards`
--
ALTER TABLE `hr_timecards`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_cutoff_period`
--
ALTER TABLE `hr_cutoff_period`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_phil_healths`
--
ALTER TABLE `hr_phil_healths`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_tax_table`
--
ALTER TABLE `hr_tax_table`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_timecards`
--
ALTER TABLE `hr_timecards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
