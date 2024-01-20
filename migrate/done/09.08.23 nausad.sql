-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 09, 2023 at 06:25 PM
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
-- Table structure for table `cpdo_imperial_system`
--

CREATE TABLE `cpdo_imperial_system` (
  `id` bigint UNSIGNED NOT NULL,
  `cis_code` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cis_imperial_system` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cis_status` tinyint NOT NULL,
  `is_active` tinyint DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpdo_imperial_system`
--

INSERT INTO `cpdo_imperial_system` (`id`, `cis_code`, `cis_imperial_system`, `cis_status`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '#000654654', 'Testing Imperial System 52', 1, 1, 1, 1, '2023-08-10 05:16:28', '2023-08-10 05:49:17'),
(2, '#000654682', 'Testing Imperial System 22', 1, 1, 1, 1, '2023-08-10 05:31:34', '2023-08-10 06:02:54'),
(3, '#0006546963', 'Lorem Ipsum Dolor Sit Amet Lorem', 1, 1, 1, 1, '2023-08-10 06:04:51', '2023-08-10 06:05:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cpdo_imperial_system`
--
ALTER TABLE `cpdo_imperial_system`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cpdo_imperial_system`
--
ALTER TABLE `cpdo_imperial_system`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
