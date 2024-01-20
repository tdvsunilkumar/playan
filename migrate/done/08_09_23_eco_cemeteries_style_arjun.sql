-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2023 at 05:48 AM
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
-- Table structure for table `eco_cemeteries_style`
--

CREATE TABLE `eco_cemeteries_style` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eco_cemetery_style` varchar(100) NOT NULL,
  `ecs_status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eco_cemeteries_style`
--

INSERT INTO `eco_cemeteries_style` (`id`, `eco_cemetery_style`, `ecs_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'mausoleum', 1, 1, 1, '2023-09-08 06:11:16', '2023-09-08 06:11:16'),
(2, 'Columbarium', 1, 1, 1, '2023-09-08 06:16:21', '2023-09-08 06:16:21'),
(3, 'Green Burial', 1, 1, 1, '2023-09-08 06:16:41', '2023-09-08 06:16:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eco_cemeteries_style`
--
ALTER TABLE `eco_cemeteries_style`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eco_cemeteries_style`
--
ALTER TABLE `eco_cemeteries_style`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
