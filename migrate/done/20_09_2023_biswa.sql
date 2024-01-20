-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2023 at 11:19 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

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
-- Table structure for table `eco_housing_application`
--

CREATE TABLE `eco_housing_application` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'unique code and primary key of the table',
  `type_of_transaction_id` int(11) NOT NULL COMMENT 'ref-Table : eco_type_of_transaction . Id',
  `app_date` date NOT NULL,
  `client_id` int(11) NOT NULL COMMENT 'ref-Table citizen . Id',
  `contact_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barangay_id` int(11) NOT NULL COMMENT 'ref-Table : barangay . Id',
  `month_terms` int(11) NOT NULL,
  `terms_date_from` date NOT NULL,
  `terms_date_to` date NOT NULL,
  `total_amount` double(14,2) NOT NULL,
  `initial_monthly` double(14,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eco_housing_application`
--
ALTER TABLE `eco_housing_application`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eco_housing_application`
--
ALTER TABLE `eco_housing_application`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Table structure for table `eco_housing_application_loc`
--

CREATE TABLE `eco_housing_application_loc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `housing_application_id` int(11) NOT NULL COMMENT 'ref-Table : eco_housing_application id',
  `residential_name_id` int(11) NOT NULL COMMENT 'ref-Table : eco_residential_name . Id',
  `residential_location_id` int(11) NOT NULL COMMENT 'ref-Table :  eco_residential_location . Id (dropdown phase of any)',
  `blk_lot_id` int(11) NOT NULL COMMENT 'ref-Table : eco_residentiallocation_details . Id ( Block and Lot dropdown)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eco_housing_application_loc`
--
ALTER TABLE `eco_housing_application_loc`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eco_housing_application_loc`
--
ALTER TABLE `eco_housing_application_loc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

