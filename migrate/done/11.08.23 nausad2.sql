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
-- Table structure for table `cpdo_development_permit_computation`
--

CREATE TABLE `cpdo_development_permit_computation` (
  `id` bigint UNSIGNED NOT NULL,
  `cm_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cis_status` tinyint DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpdo_development_permit_computation`
--

INSERT INTO `cpdo_development_permit_computation` (`id`, `cm_id`, `cis_status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_active`) VALUES
(1, '12', 1, 1, 1, '2023-08-11 06:09:12', '2023-08-11 08:26:08', 1),
(2, '12', 1, 1, 1, '2023-08-11 07:20:53', '2023-08-11 07:20:53', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cpdo_development_permit_computation`
--
ALTER TABLE `cpdo_development_permit_computation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cpdo_development_permit_computation`
--
ALTER TABLE `cpdo_development_permit_computation`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
