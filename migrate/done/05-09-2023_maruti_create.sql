-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2023 at 08:17 AM
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
-- Table structure for table `reg_burial`
--

CREATE TABLE `reg_burial` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expired_id` int(11) NOT NULL COMMENT 'Ref-table: citizens.id',
  `expired_name` varchar(255) NOT NULL COMMENT 'Full name coming from table: citizens',
  `death_caused` text NOT NULL COMMENT 'Cause of Death',
  `death_date` date NOT NULL COMMENT 'Date of Death',
  `cm_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: eco_cemeteries.id',
  `is_infectious` int(11) DEFAULT NULL COMMENT 'In case of disinterment 1 = Infectious, 2 = Non-infectious',
  `is_embalmed` int(11) DEFAULT NULL COMMENT '1 = Embalmed, 2 = Not Embalmed',
  `disposition_date` date DEFAULT NULL COMMENT 'Disposition of Remains',
  `cashierd_id` int(11) NOT NULL COMMENT 'Ref-Table: cto_cashier_details.id',
  `cashier_id` int(11) NOT NULL COMMENT 'Ref-Table:cto_cashier.id',
  `or_no` varchar(255) NOT NULL COMMENT 'Ref-Table: cto_cashier.or_no',
  `or_date` date NOT NULL COMMENT 'Ref-Table: cto_cashier.cashier_or_date',
  `or_amount` double(10,2) NOT NULL COMMENT 'Ref-Table: cto_cashier_details.tfc_amount',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reg_burial`
--

INSERT INTO `reg_burial` (`id`, `expired_id`, `expired_name`, `death_caused`, `death_date`, `cm_id`, `is_infectious`, `is_embalmed`, `disposition_date`, `cashierd_id`, `cashier_id`, `or_no`, `or_date`, `or_amount`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 4, 'Maria San Juan', '4', '2023-01-01', NULL, 1, NULL, '2023-09-08', 279, 142, '0025011', '2023-09-05', 2000.00, 1, 0, '2023-09-05 07:49:39', NULL),
(2, 5, 'Rogelmar asdasdas Denopol', '1', '2023-09-08', 1, NULL, NULL, '2023-09-14', 280, 143, '0025012', '2023-09-05', 2000.00, 1, 0, '2023-09-05 08:46:10', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reg_burial`
--
ALTER TABLE `reg_burial`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reg_burial`
--
ALTER TABLE `reg_burial`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
