-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2023 at 07:55 AM
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
-- Table structure for table `files_hr_cos`
--

CREATE TABLE `files_hr_cos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrcos_id` int(11) NOT NULL COMMENT 'ref-Table: hr_change_of_schedule.hrcos_id',
  `hrcos_file_name` varchar(255) NOT NULL COMMENT 'filename',
  `hrcos_file_path` varchar(255) NOT NULL COMMENT 'file path',
  `hrcos_file_type` varchar(50) NOT NULL COMMENT 'file type',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files_hr_cos`
--

INSERT INTO `files_hr_cos` (`id`, `hrcos_id`, `hrcos_file_name`, `hrcos_file_path`, `hrcos_file_type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '035939document1.txt', 'humanresource/changeschedule/1', 'txt', 1, NULL, '2023-07-10 10:29:39', NULL),
(2, 1, '035939document1.txt', 'humanresource/changeschedule/1', 'txt', 1, NULL, '2023-07-10 10:29:39', NULL),
(3, 1, '101823document1.txt', 'humanresource/changeschedule/1', 'txt', 1, NULL, '2023-07-11 04:48:23', NULL),
(4, 8, '114736document8.pdf', 'humanresource/changeschedule/8', 'pdf', 1, NULL, '2023-07-21 06:17:36', NULL),
(5, 8, '114736document8.txt', 'humanresource/changeschedule/8', 'txt', 1, NULL, '2023-07-21 06:17:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `files_hr_leaves`
--

CREATE TABLE `files_hr_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrl_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave.hrl_id',
  `hrl_file_name` varchar(255) NOT NULL COMMENT 'filename',
  `hrl_file_path` varchar(255) NOT NULL COMMENT 'file path',
  `hrl_file_type` varchar(50) NOT NULL COMMENT 'file type',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files_hr_leaves`
--

INSERT INTO `files_hr_leaves` (`id`, `hrl_id`, `hrl_file_name`, `hrl_file_path`, `hrl_file_type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 5, '115339document5.txt', 'humanresource/leaves/5', 'txt', 1, NULL, '2023-07-21 06:23:39', NULL),
(3, 6, '115523document6.pdf', 'humanresource/leaves/6', 'pdf', 1, NULL, '2023-07-21 06:25:23', NULL),
(4, 6, '115523document6.txt', 'humanresource/leaves/6', 'txt', 1, NULL, '2023-07-21 06:25:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `files_hr_official_work`
--

CREATE TABLE `files_hr_official_work` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrow_id` int(11) NOT NULL COMMENT 'ref-Table: hr_official_work.id',
  `fhow_file_name` varchar(255) NOT NULL COMMENT 'filename',
  `fhow_file_path` varchar(255) NOT NULL COMMENT 'file path',
  `fhow_file_type` varchar(50) NOT NULL COMMENT 'file type',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files_hr_official_work`
--

INSERT INTO `files_hr_official_work` (`id`, `hrow_id`, `fhow_file_name`, `fhow_file_path`, `fhow_file_type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '031445document1.txt', 'humanresource/officialwork/1', 'txt', 1, NULL, '2023-07-11 09:44:45', NULL),
(2, 1, '031445document1.txt', 'humanresource/officialwork/1', 'txt', 1, NULL, '2023-07-11 09:44:45', NULL),
(3, 2, '031935document2.txt', 'humanresource/officialwork/2', 'txt', 1, NULL, '2023-07-11 09:49:35', NULL),
(4, 2, '031935document2.txt', 'humanresource/officialwork/2', 'txt', 1, NULL, '2023-07-11 09:49:35', NULL),
(5, 4, '115742document4.pdf', 'humanresource/officialwork/4', 'pdf', 1, NULL, '2023-07-21 06:27:42', NULL),
(6, 5, '115907document5.pdf', 'humanresource/officialwork/5', 'pdf', 1, NULL, '2023-07-21 06:29:07', NULL),
(7, 5, '115907document5.txt', 'humanresource/officialwork/5', 'txt', 1, NULL, '2023-07-21 06:29:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `files_hr_offset`
--

CREATE TABLE `files_hr_offset` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hro_id` int(11) NOT NULL COMMENT 'ref-Table: hr_offset.id',
  `fhro_file_name` varchar(255) NOT NULL COMMENT 'filename',
  `fhro_file_path` varchar(255) NOT NULL COMMENT 'file path',
  `fhro_file_type` varchar(50) NOT NULL COMMENT 'file type',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files_hr_offset`
--

INSERT INTO `files_hr_offset` (`id`, `hro_id`, `fhro_file_name`, `fhro_file_path`, `fhro_file_type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '121536document1.txt', 'humanresource/offset/1', 'txt', 1, NULL, '2023-07-17 06:45:36', NULL),
(2, 2, '120232document2.pdf', 'humanresource/offset/2', 'pdf', 1, NULL, '2023-07-21 06:32:32', NULL),
(3, 2, '120232document2.txt', 'humanresource/offset/2', 'txt', 1, NULL, '2023-07-21 06:32:32', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files_hr_cos`
--
ALTER TABLE `files_hr_cos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files_hr_leaves`
--
ALTER TABLE `files_hr_leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files_hr_official_work`
--
ALTER TABLE `files_hr_official_work`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files_hr_offset`
--
ALTER TABLE `files_hr_offset`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files_hr_cos`
--
ALTER TABLE `files_hr_cos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `files_hr_leaves`
--
ALTER TABLE `files_hr_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `files_hr_official_work`
--
ALTER TABLE `files_hr_official_work`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `files_hr_offset`
--
ALTER TABLE `files_hr_offset`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
