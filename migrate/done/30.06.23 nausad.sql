-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2023 at 01:15 PM
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
-- Table structure for table `ho_medical_certificates`
--

CREATE TABLE `ho_medical_certificates` (
  `id` bigint UNSIGNED NOT NULL,
  `cit_id` int NOT NULL,
  `cit_age` int NOT NULL,
  `cashierd_id` int NOT NULL,
  `cashier_id` int NOT NULL,
  `or_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `or_date` date NOT NULL,
  `or_amount` decimal(10,2) NOT NULL,
  `med_cert_is_free` int NOT NULL,
  `med_officer_id` int NOT NULL,
  `med_officer_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `med_officer_approved_status` tinyint NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_medical_certificates`
--

INSERT INTO `ho_medical_certificates` (`id`, `cit_id`, `cit_age`, `cashierd_id`, `cashier_id`, `or_no`, `or_date`, `or_amount`, `med_cert_is_free`, `med_officer_id`, `med_officer_position`, `med_officer_approved_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 25, 117, 54, '54', '2023-06-23', 0.08, 0, 25, 'Department Head', 1, 1, 1, '2023-06-30 15:56:06', '2023-06-30 15:56:06'),
(2, 1, 25, 117, 54, '0000029', '2023-06-23', 0.08, 1, 30, 'Department Head', 1, 1, 1, '2023-06-30 16:00:04', '2023-06-30 16:00:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_medical_certificates`
--
ALTER TABLE `ho_medical_certificates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_medical_certificates`
--
ALTER TABLE `ho_medical_certificates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
