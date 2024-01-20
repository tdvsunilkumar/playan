-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2023 at 03:44 AM
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
-- Table structure for table `eng_building_permit_fees_division`
--


--
-- Table structure for table `eng_building_permit_fees_set1`
--

CREATE TABLE `eng_building_permit_fees_set1` (
  `ebpfs1_id` bigint(20) UNSIGNED NOT NULL,
  `ebpfs1_range_from` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs1_range_to` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs1_fees` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `eef_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eng_building_permit_fees_set1`
--

INSERT INTO `eng_building_permit_fees_set1` (`ebpfs1_id`, `ebpfs1_range_from`, `ebpfs1_range_to`, `ebpfs1_fees`, `eef_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 0.00, 19.99, 2.00, 1, 1, 1, '2023-08-19 06:49:13', '2023-08-19 06:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `eng_building_permit_fees_set2`
--

CREATE TABLE `eng_building_permit_fees_set2` (
  `ebpfs2_id` bigint(20) UNSIGNED NOT NULL,
  `ebpfs2_range_from` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs2_range_to` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs2_fees` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs2_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eng_building_permit_fees_set2`
--

INSERT INTO `eng_building_permit_fees_set2` (`ebpfs2_id`, `ebpfs2_range_from`, `ebpfs2_range_to`, `ebpfs2_fees`, `ebpfs2_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 0.00, 19.99, 2.00, 1, 1, 1, '2023-08-19 07:23:09', '2023-08-19 07:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `eng_building_permit_fees_set3`
--

CREATE TABLE `eng_building_permit_fees_set3` (
  `ebpfs3_id` bigint(20) UNSIGNED NOT NULL,
  `ebpfs3_range_from` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs3_range_to` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs3_fees` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs3_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eng_building_permit_fees_set4`
--

CREATE TABLE `eng_building_permit_fees_set4` (
  `ebpfs4_id` bigint(20) UNSIGNED NOT NULL,
  `ebpfs4_range_from` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs4_range_to` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs4_fees` double(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `ebpfs4_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--


--
-- Indexes for table `eng_building_permit_fees_set1`
--
ALTER TABLE `eng_building_permit_fees_set1`
  ADD PRIMARY KEY (`ebpfs1_id`);

--
-- Indexes for table `eng_building_permit_fees_set2`
--
ALTER TABLE `eng_building_permit_fees_set2`
  ADD PRIMARY KEY (`ebpfs2_id`);

--
-- Indexes for table `eng_building_permit_fees_set3`
--
ALTER TABLE `eng_building_permit_fees_set3`
  ADD PRIMARY KEY (`ebpfs3_id`);

--
-- Indexes for table `eng_building_permit_fees_set4`
--
ALTER TABLE `eng_building_permit_fees_set4`
  ADD PRIMARY KEY (`ebpfs4_id`);

--
-- AUTO_INCREMENT for dumped tables


--
-- AUTO_INCREMENT for table `eng_building_permit_fees_set1`
--
ALTER TABLE `eng_building_permit_fees_set1`
  MODIFY `ebpfs1_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `eng_building_permit_fees_set2`
--
ALTER TABLE `eng_building_permit_fees_set2`
  MODIFY `ebpfs2_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `eng_building_permit_fees_set3`
--
ALTER TABLE `eng_building_permit_fees_set3`
  MODIFY `ebpfs3_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eng_building_permit_fees_set4`
--
ALTER TABLE `eng_building_permit_fees_set4`
  MODIFY `ebpfs4_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
