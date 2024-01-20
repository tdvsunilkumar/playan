-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2023 at 04:19 AM
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
-- Table structure for table `eng_building_permit_fees`
--

CREATE TABLE `eng_building_permit_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ejr_id` int(11) NOT NULL COMMENT 'job request id',
  `ebpfd_id` int(11) NOT NULL COMMENT 'eng division id',
  `ebpf_total_sqm` double NOT NULL,
  `ebpf_total_fees` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eng_building_permit_fees`
--

INSERT INTO `eng_building_permit_fees` (`id`, `ejr_id`, `ebpfd_id`, `ebpf_total_sqm`, `ebpf_total_fees`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(4, 1, 4, 600, 600, 1, 0, '2023-08-24 05:04:48', '2023-08-24 05:04:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eng_building_permit_fees`
--
ALTER TABLE `eng_building_permit_fees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eng_building_permit_fees`
--
ALTER TABLE `eng_building_permit_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
