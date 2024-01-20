-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2023 at 11:15 AM
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

CREATE TABLE `eng_building_permit_fees_division` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ebpfd_group` varchar(50) DEFAULT NULL,
  `ebpfd_description` varchar(200) DEFAULT NULL,
  `ebpfc_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table: eng_building_permit_fees_category.ebpfc_id',
  `ebpfd_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=inactive,1=active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eng_building_permit_fees_division`
--

INSERT INTO `eng_building_permit_fees_division` (`id`, `ebpfd_group`, `ebpfd_description`, `ebpfc_id`, `ebpfd_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'A - Division 1', 'SSSgg', 2, 1, 1, 1, '2023-08-18 11:40:20', '2023-08-18 11:42:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eng_building_permit_fees_division`
--
ALTER TABLE `eng_building_permit_fees_division`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eng_building_permit_fees_division`
--
ALTER TABLE `eng_building_permit_fees_division`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
