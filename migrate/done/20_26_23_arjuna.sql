-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2023 at 08:54 AM
-- Server version: 10.10.2-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Table structure for table `ho_serology_method`
--

CREATE TABLE IF NOT EXISTS `ho_serology_method` (
  `id` bigint(20) unsigned NOT NULL,
  `ser_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table: ho_service_id where ho_service_form = 2',
  `ser_m_method` varchar(100) NOT NULL DEFAULT '0',
  `ser_m_remarks` varchar(200) DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_serology_method`
--

INSERT INTO `ho_serology_method` (`id`, `ser_id`, `ser_m_method`, `ser_m_remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 22, 'Immunochromatography', NULL, 1, 1, '2023-06-20 12:24:46', '2023-06-20 12:24:46'),
(2, 22, 'RPR Qualitative Method', NULL, 1, 1, '2023-06-20 12:25:02', '2023-06-20 12:25:02'),
(3, 22, 'RPR Quantitative Method', NULL, 1, 1, '2023-06-20 12:25:37', '2023-06-20 12:25:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_serology_method`
--
ALTER TABLE `ho_serology_method`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_serology_method`
--
ALTER TABLE `ho_serology_method`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
