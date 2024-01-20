-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2023 at 08:53 AM
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
-- Table structure for table `ho_serology_details`
--

CREATE TABLE IF NOT EXISTS `ho_serology_details` (
  `id` bigint(20) unsigned NOT NULL,
  `ser_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table: ho_serology.id',
  `ho_service_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table: ho_service_id',
  `sm_id` int(11) DEFAULT 0 COMMENT 'Ref-Table: ho_serology_method.id',
  `ser_specimen` varchar(20) DEFAULT '0',
  `ser_brand` varchar(20) DEFAULT '0',
  `ser_lot` varchar(20) DEFAULT '0',
  `ser_exp` date DEFAULT NULL,
  `ser_result` int(11) DEFAULT 0 COMMENT '0 is Non-Reactive, 1 is Reactive',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_serology_details`
--
ALTER TABLE `ho_serology_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_serology_details`
--
ALTER TABLE `ho_serology_details`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
