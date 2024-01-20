-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2023 at 09:41 AM
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
-- Table structure for table `eng_electrical_fees_ups`
--

CREATE TABLE `eng_electrical_fees_ups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eefu_kva_range_from` double(8,3) UNSIGNED NOT NULL,
  `eefu_kva_range_to` double(8,3) UNSIGNED NOT NULL,
  `eefu_fees` double(8,3) UNSIGNED NOT NULL,
  `eefu_in_excess_fees` double(8,3) UNSIGNED NOT NULL,
  `eefu_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eng_electrical_fees_ups`
--

INSERT INTO `eng_electrical_fees_ups` (`id`, `eefu_kva_range_from`, `eefu_kva_range_to`, `eefu_fees`, `eefu_in_excess_fees`, `eefu_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 55.000, 99.000, 99.000, 7888.000, 1, 1, 1, '2023-08-16 10:06:14', '2023-08-16 10:10:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eng_electrical_fees_ups`
--
ALTER TABLE `eng_electrical_fees_ups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eng_electrical_fees_ups`
--
ALTER TABLE `eng_electrical_fees_ups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
